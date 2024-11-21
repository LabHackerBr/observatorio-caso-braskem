import { __ } from '@wordpress/i18n'

const { useEffect, useState } = wp.element

import { useSelect } from '@wordpress/data'
import { useInstanceId } from "@wordpress/compose"

import ServerSideRender from '@wordpress/server-side-render'
import apiFetch from '@wordpress/api-fetch'
import { useBlockProps, InspectorControls } from '@wordpress/block-editor'
import LinkSelector from '../../shared/components/LinkSelector'
import SelectGuestAuthor from '../../shared/components/SelectGuestAuthor'
import SelectPostType from "../../shared/components/SelectPostType"
import SelectTerms from "../../shared/components/SelectTerms"

import {
    __experimentalNumberControl as NumberControl,
    Disabled,
    TextControl,
    ToggleControl,
    PanelBody,
    PanelRow,
    RangeControl,
    SelectControl
} from '@wordpress/components'

import metadata from './block.json'
import './editor.scss'

export default function Edit( { attributes, setAttributes } ) {

    const currentPostId = useSelect((select) => {
        return select('core/editor').getCurrentPostId()
    }, [])

    const instanceId = useInstanceId(Edit, 'latest-horizontal-posts-' + currentPostId)

    const blockProps = useBlockProps( {
        className: 'latest-horizontal-posts-block'
    } )

    const {
        blockId,
        blockModel,
        channelId,
        coAuthor,
        compare,
        contentPosition,
        description,
        flickrAlbumId,
        flickrAPIKey,
        flickrByType,
        flickrUserId,
        heading,
        noCompare,
        noPostType,
        noQueryTerms,
        noTaxonomy,
        playlistId,
        postsToShow,
        postType,
        queryTerms,
        showChildren,
        showTaxonomy,
        slidesToShow,
        taxonomy,
        videoModel
    } = attributes

    useEffect(() => {
        if (!blockId || blockId !== instanceId) {
            setAttributes({ blockId: instanceId })
        }
    })

    const onChangeBlockModel = ( value ) => {
        setAttributes( { blockModel: value } )
        setAttributes( { showTaxonomy: '' } )
        setAttributes( { taxonomy: '' } )
        setAttributes( { queryTerms: [] } )
        setAttributes( { coAuthor: '' } )
    }

    const onChangeContentPosition = ( value ) => {
        setAttributes( { contentPosition: value } )
    }

    const onChangeHeading = ( newHeading ) => {
        setAttributes( { heading: newHeading } )
    }

    const onChangePostType = ( value ) => {
        setAttributes( { postType: value } )
        setAttributes( { showTaxonomy: '' } )
        setAttributes( { taxonomy: '' } )
        setAttributes( { queryTerms: [] } )
    }

    const onChangeSelectTerm = ( value ) => {
        setAttributes( { queryTerms: value.length > 0 ? value : undefined } )
    }

    const onChangeTaxonomy = ( value ) => {
        setAttributes( { taxonomy: value } )
        setAttributes( { showTaxonomy: '' } )
        setAttributes( { queryTerms: [] } )
    }

    const onChangeCompare = ( value ) => {
        setAttributes( { compare: value == 'OR' ? 'OR' : 'AND' } )
    }

    // No
    const onChangeNoPostType = ( value ) => {
        setAttributes( { noPostType: value } )
    }

    const onChangeNoTaxonomy = ( value ) => {
        setAttributes( { noTaxonomy: value } )
    }

    const onChangeNoSelectTerm = ( value ) => {
        setAttributes( { noQueryTerms: value.length > 0 ? value : undefined } )
    }

    const onChangeNoCompare = ( value ) => {
        setAttributes( { noCompare: value == 'OR' ? 'OR' : 'AND' } )
    }

    // Get taxonomies from the post type selected
    const [taxonomies, setTaxonomies] = useState([])

    useEffect(() => {
        if (postType) {
        apiFetch({ path: `/hacklabr/v1/taxonomias/${postType}` })
            .then(taxonomies => {
                setTaxonomies(taxonomies)
            })
        }
    }, [postType])

    const onChangeCoAuthor = (value) => {
        setAttributes({ coAuthor: value })
    }

    // Get taxonomies from the post type selected
    const [noTaxonomies, setNoTaxonomies] = useState([])

    useEffect(() => {
        if(noPostType) {
            apiFetch({ path: `/hacklabr/v1/taxonomias/${noPostType}` })
                .then((noTaxonomies) => {
                    setNoTaxonomies(noTaxonomies)
            })
        }
    }, [noPostType])

    return (
        <>
            <InspectorControls>
                <PanelBody
                    className="latest-horizontal-posts-block-inspector-controls"
                    title={ __( 'Layout', 'hacklabr' ) }
                    initialOpen={ false }
                >
                    <PanelRow>
                        <TextControl
                            label={ __( 'Heading', 'hacklabr' ) }
                            value={ heading }
                            onChange={ onChangeHeading }
                            help={ __(
                                'The block title. Leave blank to not display',
                                'hacklabr'
                            ) }
                        />
                    </PanelRow>

                    <PanelRow className='high-spot-block-link-selector'>
                        <LinkSelector attributes={ attributes } setAttributes={ setAttributes } />
                    </PanelRow>

                    <PanelRow>
                        <TextControl
                            label={ __( 'Description', 'hacklabr' ) }
                            value={ description }
                            onChange={ ( value ) => { setAttributes( { description: value } ) } }
                            help={ __(
                                'The block description. Leave blank to not display',
                                'hacklabr'
                            ) }
                        />
                    </PanelRow>

                    <PanelRow>
                        <SelectControl
                            label={ __( 'Block model', 'hacklabr' ) }
                            value={ blockModel }
                            options={ [
                                {
                                    label: __( 'Albums (Flickr)', 'hacklabr' ),
                                    value: "albums"
                                },
                                {
                                    label: __( 'Photos (Flickr)', 'hacklabr' ),
                                    value: "collection"
                                },
                                {
                                    label: __( 'Columnists', 'hacklabr' ),
                                    value: "columnists"
                                },
                                {
                                    label: __( 'Most read (Posts)', 'hacklabr' ),
                                    value: "most-read"
                                },
                                {
                                    label: __( 'Latest posts', 'hacklabr' ),
                                    value: "post"
                                },
                                {
                                    label: __( 'Videos (YouTube)', 'hacklabr' ),
                                    value: "videos"
                                }
                            ] }
                            onChange={ onChangeBlockModel }
                        />
                    </PanelRow>

                    <PanelRow>
                        <SelectControl
                            label={ __( 'Content position', 'hacklabr' ) }
                            value={ contentPosition }
                            options={[
                                {
                                    label: __( 'Left', 'hacklabr' ),
                                    value: "left"
                                },
                                {
                                    label: __( 'Right', 'hacklabr' ),
                                    value: "right"
                                },
                                {
                                    label: __( 'Full width', 'hacklabr' ),
                                    value: "full"
                                }
                            ]}
                            onChange={ onChangeContentPosition }
                        />
                    </PanelRow>

                    <PanelRow>
                        <NumberControl
                            label={ __( 'Slides to show', 'hacklabr' ) }
                            max={ 5 }
                            min={ 3 }
                            onChange={ ( value ) => { setAttributes( { slidesToShow: parseInt(value) } ) } }
                            step={ 1 }
                            value={ slidesToShow }
                        />
                    </PanelRow>
                </PanelBody>
                <PanelBody
                    className="latest-horizontal-posts-block-inspector-controls"
                    title={ __( 'Query', 'hacklabr' ) }
                    initialOpen={ false }
                >
                    { ( blockModel === 'videos' ) && (
                        <>
                            <PanelRow>
                                <SelectControl
                                    label={ __( 'Type of the video', 'hacklabr' ) }
                                    value={ videoModel }
                                    options={[
                                        {
                                            label: __( 'By playlist', 'hacklabr' ),
                                            value: "playlist"
                                        },
                                        {
                                            label: __( 'By channel', 'hacklabr' ),
                                            value: "channel"
                                        }
                                    ]}
                                    onChange={ ( value ) => {
                                        setAttributes( { videoModel: value } )
                                    } }
                                />
                            </PanelRow>

                            <PanelRow>
                                <RangeControl
                                    label={ __( 'Total number of posts to display', 'hacklabr' ) }
                                    value={ postsToShow }
                                    onChange={ ( value ) => setAttributes( { postsToShow: value } ) }
                                    min={ 2 }
                                    max={ 10 }
                                    step={ 1 }
                                />
                            </PanelRow>

                            { ( videoModel === 'playlist' ) && (
                                <PanelRow>
                                    <TextControl
                                        label={ __( 'YouTube playlist ID', 'hacklabr' ) }
                                        value={ playlistId }
                                        onChange={ ( value ) => { setAttributes( { playlistId: value } ) } }
                                    />
                                </PanelRow>
                            ) }

                            { ( videoModel === 'channel' ) && (
                                <PanelRow>
                                    <TextControl
                                        label={ __( 'YouTube channel ID', 'hacklabr' ) }
                                        value={ channelId }
                                        onChange={ ( value ) => { setAttributes( { channelId: value } ) } }
                                    />
                                </PanelRow>
                            ) }
                        </>
                    ) }

                    { ( blockModel === 'collection' || blockModel === 'albums' ) && (
                        <>
                            <PanelRow>
                                <TextControl
                                    label={ __( 'Flickr API Key', 'hacklabr' ) }
                                    value={ flickrAPIKey }
                                    onChange={ ( value ) => { setAttributes( { flickrAPIKey: value } ) } }
                                />
                            </PanelRow>

                            { ( blockModel === 'collection' ) && (
                                <PanelRow>
                                    <SelectControl
                                        label={ __( 'Type of the content', 'hacklabr' ) }
                                        value={ flickrByType }
                                        options={[
                                            {
                                                label: __( 'Images by user', 'hacklabr' ),
                                                value: "user"
                                            },
                                            {
                                                label: __( 'Images by album', 'hacklabr' ),
                                                value: "album"
                                            }
                                        ]}
                                        onChange={ ( value ) => {
                                            setAttributes( { flickrByType: value } )
                                        } }
                                    />
                                </PanelRow>
                            ) }

                            <PanelRow>
                                { ( blockModel === 'collection' && flickrByType === 'album' ) ? (
                                    <TextControl
                                        label={ __( 'Album ID', 'hacklabr' ) }
                                        value={ flickrAlbumId }
                                        onChange={ ( value ) => {
                                            setAttributes( { flickrAlbumId: value } )
                                        } }
                                    />
                                ) : (
                                    <TextControl
                                        label={ __( 'User ID', 'hacklabr' ) }
                                        value={ flickrUserId }
                                        onChange={ ( value ) => {
                                            setAttributes( { flickrUserId: value } )
                                        } }
                                    />
                                ) }
                            </PanelRow>
                        </>
                    ) }

                    { ( blockModel === 'most-read' || blockModel === 'post' ) && (
                        <>
                            <PanelRow>
                                <SelectPostType postType={postType} onChangePostType={onChangePostType} />
                            </PanelRow>

                            <PanelRow>
                                <ToggleControl
                                    label={ __( 'Show children items (if any)?', 'hacklabr' ) }
                                    checked={ showChildren }
                                    onChange={ () => { setAttributes( { showChildren: ! showChildren } ) } }
                                />
                            </PanelRow>

                            <PanelRow>
                                <SelectControl
                                    label={ __( 'Taxonomy to filter', 'hacklabr' ) }
                                    value={ taxonomy }
                                    options={ taxonomies.map( taxonomy => ( {
                                        label: taxonomy.label,
                                        value: taxonomy.value
                                    }))}
                                    onChange={ onChangeTaxonomy }
                                    help={ __(
                                        'Leave blank to not filter by taxonomy',
                                        'hacklabr'
                                    ) }
                                />
                            </PanelRow>

                            { taxonomy && (
                                <PanelRow>
                                    <SelectTerms onChangeSelectTerm={ onChangeSelectTerm } selectedTerms={ queryTerms } taxonomy={ taxonomy } />
                                </PanelRow>
                            ) }

                            { queryTerms?.length > 1 && (
                                <PanelRow>
                                    <SelectControl
                                        label={ __( 'Compare terms', 'hacklabr' ) }
                                        value={ compare }
                                        options={ [
                                            {
                                                label: __( 'OR', 'hacklabr' ),
                                                value: "or"
                                            },
                                            {
                                                label: __( 'AND', 'hacklabr' ),
                                                value: "and"
                                            }
                                        ]}
                                        onChange={ onChangeCompare }
                                    />
                                </PanelRow>
                            ) }


                            { ( blockModel === 'most-read' || blockModel === 'post' ) && (
                                <PanelRow>
                                    <SelectGuestAuthor coAuthor={ coAuthor } onChangeCoAuthor={ onChangeCoAuthor } />
                                </PanelRow>
                            ) }

                            <PanelRow>
                                <RangeControl
                                    label={ __( 'Total number of posts to display', 'hacklabr' ) }
                                    value={ postsToShow }
                                    onChange={ ( value ) => setAttributes( { postsToShow: value } ) }
                                    min={ 2 }
                                    max={ 20 }
                                    step={ 2 }
                                />
                            </PanelRow>

                            <PanelRow>
                                <SelectControl
                                    label={ __( 'Show taxonomy, which?', 'hacklabr' ) }
                                    value={ showTaxonomy }
                                    options={ taxonomies.map( taxonomy => ( {
                                        label: taxonomy.label,
                                        value: taxonomy.value
                                    } ) ) }
                                    onChange={ ( value ) => setAttributes( { showTaxonomy: value } ) }
                                    help={ __(
                                        'Leave blank to not display any taxonomy',
                                        'hacklabr'
                                    ) }
                                />
                            </PanelRow>

                            <PanelRow>
                                <h2>{ __( 'Filter posts to not display', 'hacklabr' ) }</h2>
                            </PanelRow>

                            <PanelRow>
                                <SelectPostType postType={ noPostType } onChangePostType={ onChangeNoPostType } />
                            </PanelRow>

                            { noPostType && (
                                <PanelRow>
                                    <SelectControl
                                        label={ __( 'Taxonomy to filter', 'hacklabr' ) }
                                        value={ noTaxonomy }
                                        options={ noTaxonomies.map( noTaxonomy => ( {
                                            label: noTaxonomy.label,
                                            value: noTaxonomy.value
                                        }))}
                                        onChange={ onChangeNoTaxonomy }
                                        help={ __(
                                            'Leave blank to not filter by taxonomy',
                                            'hacklabr'
                                        ) }
                                    />
                                </PanelRow>
                            ) }

                            { noTaxonomy && (
                                <PanelRow>
                                    <SelectTerms onChangeSelectTerm={ onChangeNoSelectTerm } selectedTerms={ noQueryTerms } taxonomy={ noTaxonomy } />
                                </PanelRow>
                            ) }

                            { noQueryTerms?.length > 1 && (
                                <PanelRow>
                                    <SelectControl
                                        label={ __( 'Compare terms', 'hacklabr' ) }
                                        value={ noCompare }
                                        options={ [
                                            {
                                                label: __( 'OR', 'hacklabr' ),
                                                value: "or"
                                            },
                                            {
                                                label: __( 'AND', 'hacklabr' ),
                                                value: "and"
                                            }
                                        ]}
                                        onChange={ onChangeNoCompare }
                                    />
                                </PanelRow>
                            ) }
                        </>
                    ) }

                    { ( blockModel === 'columnists' ) && (
                        <PanelRow>
                            <h2>{ __( 'With this configuration the block will display Co-Authors', 'hacklabr' ) }</h2>
                        </PanelRow>
                    ) }
                </PanelBody>
            </InspectorControls>

            <div { ...blockProps }>
                <Disabled>
                    <ServerSideRender
                        block={ metadata.name }
                        skipBlockSupportAttributes
                        attributes={ attributes }
                    />
                </Disabled>
            </div>
        </>
    )
}

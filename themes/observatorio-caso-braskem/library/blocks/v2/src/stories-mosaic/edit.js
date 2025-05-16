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

export default function Edit( { attributes, clientId, setAttributes } ) {

    const currentPostId = useSelect((select) => {
        return select('core/editor').getCurrentPostId()
    }, [])

    const instanceId = useInstanceId(Edit, 'stories-mosaic-' + currentPostId)

    const blockProps = useBlockProps( {
        className: 'stories-mosaic-block'
    } )

    const {
        blockId,
        blockModel,
        coAuthor,
        columns,
        compare,
        contentBelow,
        flickrAlbumId,
        flickrAPIKey,
        flickrByType,
        flickrUserId,
        gridFormat,
        heading,
        noCompare,
        noPostType,
        noQueryTerms,
        noTaxonomy,
        playlistId,
        postsBySlide,
        postsToShow,
        postType,
        queryTerms,
        showAsGrid,
        showAuthor,
        showAvatar,
        showChildren,
        showDate,
        showExcerpt,
        showTaxonomy,
        showThumbnail,
        taxonomy,
        thumbnailFormat
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
        if(postType) {
            apiFetch({ path: `/hacklabr/v1/taxonomias/${postType}` })
                .then((taxonomies) => {
                    setTaxonomies(taxonomies)
                })
        }
    }, [postType])

    // Get taxonomies from the post type selected
    const [noTaxonomies, setNoTaxonomies] = useState([])

    useEffect(() => {
        if(noPostType) {
            apiFetch({ path: `/hacklabr/v1/taxonomias/${postType}` })
                .then((noTaxonomies) => {
                    setNoTaxonomies(noTaxonomies)
                })
        }
    }, [noPostType])

    const onChangeCoAuthor = (value) => {
        setAttributes({ coAuthor: value })
    }

    return (
        <>
            <InspectorControls>
                <PanelBody
                    className="stories-mosaic-block-inspector-controls"
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

                    <PanelRow className='stories-mosaic-block-link-selector'>
                        <LinkSelector attributes={ attributes } setAttributes={ setAttributes } />
                    </PanelRow>

                    <PanelRow>
                        <SelectControl
                            label={ __( 'Block model', 'hacklabr' ) }
                            value={ blockModel }
                            options={ [
                                {
                                    label: __( 'Collection', 'hacklabr' ),
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
                                    label: __( 'Numbered posts', 'hacklabr' ),
                                    value: "numbered"
                                },
                                {
                                    label: __( 'Latest posts', 'hacklabr' ),
                                    value: "post"
                                },
                                {
                                    label: __( 'Videos', 'hacklabr' ),
                                    value: "videos"
                                }
                            ]}
                            onChange={ onChangeBlockModel }
                        />
                    </PanelRow>

                    { ( blockModel !== 'numbered' ) && (
                        <PanelRow>
                            <ToggleControl
                                label={ __( 'Show the post thumbnail?', 'hacklabr' ) }
                                checked={ showThumbnail }
                                onChange={ () => { setAttributes( { showThumbnail: ! showThumbnail } ) } }
                            />
                        </PanelRow>
                    ) }

                    { ( showThumbnail && blockModel !== 'numbered' ) && (
                        <PanelRow>
                            <ToggleControl
                                label={ __( 'Use rounded thumbnail?', 'hacklabr' ) }
                                checked={ thumbnailFormat }
                                onChange={ () => { setAttributes( { thumbnailFormat: ! thumbnailFormat } ) } }
                            />
                        </PanelRow>
                    ) }

                    { ( ! thumbnailFormat ) && (
                        <PanelRow>
                            <ToggleControl
                                label={ __( 'Show content below?', 'hacklabr' ) }
                                checked={ contentBelow }
                                onChange={ () => { setAttributes( { contentBelow: ! contentBelow } ) } }
                            />
                        </PanelRow>
                    ) }

                    { ( showThumbnail && ( blockModel == 'post' || blockModel == 'most-read' ) ) && (
                        <PanelRow>
                            <ToggleControl
                                label={ __( 'Use avatar as thumbnail?', 'hacklabr' ) }
                                checked={ showAvatar }
                                onChange={ () => { setAttributes( { showAvatar: ! showAvatar } ) } }
                            />
                        </PanelRow>
                    ) }

                    { ( blockModel == 'post' || blockModel == 'numbered' || blockModel == 'columnists' || blockModel == 'most-read' ) && (
                        <>
                            <PanelRow>
                                <ToggleControl
                                    label={ __( 'Show as a Grid?', 'hacklabr' ) }
                                    checked={ showAsGrid }
                                    onChange={ () => { setAttributes( { showAsGrid: ! showAsGrid } ) } }
                                />
                            </PanelRow>

                            { ( showAsGrid ) && (
                                <>
                                    <PanelRow>
                                        <SelectControl
                                            label={ __( 'Grid format', 'hacklabr' ) }
                                            value={ gridFormat }
                                            options={ [
                                                {
                                                    label: __( 'Columns', 'hacklabr' ),
                                                    value: "columns"
                                                },
                                                {
                                                    label: __( 'Row', 'hacklabr' ),
                                                    value: "row"
                                                }
                                            ]}
                                            onChange={ ( value ) => { setAttributes( { gridFormat:  gridFormat === 'columns' ? 'row' : 'columns' } ) } }
                                        />
                                    </PanelRow>

                                    <PanelRow>
                                        <NumberControl
                                            label={ __( 'Columns', 'hacklabr' ) }
                                            value={ columns }
                                            onChange={ ( value ) => { setAttributes( { columns: value } ) } }
                                            min={ 1 }
                                            max={ 4 }
                                        />
                                    </PanelRow>
                                </>
                            ) }

                            <PanelRow>
                                <ToggleControl
                                    label={ __( 'Show the excerpt?', 'hacklabr' ) }
                                    checked={ showExcerpt }
                                    onChange={ () => { setAttributes( { showExcerpt: ! showExcerpt } ) } }
                                />
                            </PanelRow>
                        </>
                    ) }

                    { ( blockModel == 'post' || blockModel == 'numbered' || blockModel == 'most-read' ) && (
                        <>
                            <PanelRow>
                                <ToggleControl
                                    label={ __( 'Show the post author?', 'hacklabr' ) }
                                    checked={ showAuthor }
                                    onChange={ () => { setAttributes( { showAuthor: ! showAuthor } ) } }
                                />
                            </PanelRow>

                            <PanelRow>
                                <ToggleControl
                                    label={ __( 'Show the post date?', 'hacklabr' ) }
                                    checked={ showDate }
                                    onChange={ () => { setAttributes( { showDate: ! showDate } ) } }
                                />
                            </PanelRow>
                        </>
                    ) }

                    <PanelRow>
                        <NumberControl
                            label={ __( 'Posts by slide', 'hacklabr' ) }
                            max={ showAsGrid? 16 : 5 }
                            min={ 1 }
                            onChange={ ( value ) => { setAttributes( { postsBySlide: value } ) } }
                            step={ 1 }
                            value={ postsBySlide }
                        />
                    </PanelRow>

                    <PanelRow>
                        <RangeControl
                            label={ __( 'Total number of posts to display', 'hacklabr' ) }
                            value={ postsToShow }
                            onChange={ ( value ) => setAttributes( { postsToShow: value } ) }
                            min={ 3 }
                            max={ 99 }
                            step={ 1 }
                        />
                    </PanelRow>
                </PanelBody>

                <PanelBody
                    className="stories-mosaic-block-inspector-controls"
                    title={ __( 'Query', 'hacklabr' ) }
                    initialOpen={ false }
                >
                    { ( blockModel == 'videos' ) && (
                        <PanelRow>
                            <TextControl
                                label={ __( 'YouTube Playlist ID', 'hacklabr' ) }
                                value={ playlistId }
                                onChange={ ( value ) => { setAttributes( { playlistId: value } ) } }
                            />
                        </PanelRow>
                    ) }

                    { ( blockModel == 'collection' ) && (
                        <>
                            <PanelRow>
                                <TextControl
                                    label={ __( 'Flickr API Key', 'hacklabr' ) }
                                    value={ flickrAPIKey }
                                    onChange={ ( value ) => { setAttributes( { flickrAPIKey: value } ) } }
                                />
                            </PanelRow>

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

                            <PanelRow>
                                { ( flickrByType == 'album' ) && (
                                    <TextControl
                                        label={ __( 'Album ID', 'hacklabr' ) }
                                        value={ flickrAlbumId }
                                        onChange={ ( value ) => {
                                            setAttributes( { flickrAlbumId: value } )
                                        } }
                                    />
                                ) }

                                { ( flickrByType == 'user' ) && (
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

                    { ( blockModel == 'post' || blockModel == 'numbered' || blockModel == 'most-read' ) && (
                        <>
                            <PanelRow>
                                <SelectPostType postType={ postType } onChangePostType={ onChangePostType } />
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

                            { blockModel === 'most-read' && (
                                <PanelRow>
                                    <SelectGuestAuthor coAuthor={ coAuthor } onChangeCoAuthor={ onChangeCoAuthor } />
                                </PanelRow>
                            ) }

                            <PanelRow>
                                <RangeControl
                                    label={ __( 'Total number of posts to display', 'hacklabr' ) }
                                    value={ postsToShow }
                                    onChange={ ( value ) => setAttributes( { postsToShow: value } ) }
                                    min={ 3 }
                                    max={ 20 }
                                    step={ 1 }
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

                    { ( blockModel == 'columnists' ) && (
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

import { registerBlockType } from '@wordpress/blocks'
import { useInstanceId } from "@wordpress/compose"
import { useSelect } from '@wordpress/data'
import { useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'

import ServerSideRender from '@wordpress/server-side-render'
import { useBlockProps, InspectorControls } from '@wordpress/block-editor'
import { QueryPanel } from '../shared/QueryPanel'

import {
    Disabled,
    TextControl,
    ToggleControl,
    PanelBody,
    PanelRow,
    RangeControl
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
        heading,
        postsToShow,
        showAuthor,
        showDate,
        showExcerpt
    } = attributes

    useEffect(() => {
        if (!blockId || blockId !== instanceId) {
            setAttributes({ blockId: instanceId })
        }
    })

    const onChangeHeading = ( newHeading ) => {
        setAttributes( { heading: newHeading } )
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
                </PanelBody>
                <QueryPanel
                    attributes={attributes}
                    setAttributes={setAttributes}
                >
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
                </QueryPanel>
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

registerBlockType(metadata.name, {
    edit: Edit,
});

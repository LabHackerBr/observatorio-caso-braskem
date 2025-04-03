import { __ } from '@wordpress/i18n'

const { useEffect, useState } = wp.element

import { useSelect } from '@wordpress/data'
import { useInstanceId } from "@wordpress/compose"

import ServerSideRender from '@wordpress/server-side-render'
import { useBlockProps, InspectorControls } from '@wordpress/block-editor'

import {
    Disabled,
    TextControl,
    PanelBody,
    PanelRow,
    RangeControl
} from '@wordpress/components'

import metadata from './block.json'
import './editor.scss'

export default function Edit( { attributes, setAttributes } ) {

    const currentPostId = useSelect( ( select ) => {
        return select( 'core/editor' ).getCurrentPostId()
    }, [] )

    const instanceId = useInstanceId( Edit, 'events-from-mapas-culturais-' + currentPostId )

    const blockProps = useBlockProps( {
        className: 'events-from-mapas-culturais-block'
    } )

    const {
        blockId,
        heading,
        eventsToShow
    } = attributes

    useEffect(() => {
        if ( ! blockId || blockId !== instanceId ) {
            setAttributes( { blockId: instanceId } )
        }
    })

    const onChangeHeading = ( newHeading ) => {
        setAttributes( { heading: newHeading } )
    }

    return (
        <>
            <InspectorControls>
                <PanelBody
                    className="events-from-mapas-culturais-block-inspector-controls"
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
                    <PanelRow>
                        <RangeControl
                            label={ __( 'Events to display', 'hacklabr' ) }
                            value={ eventsToShow }
                            onChange={ ( value ) => setAttributes( { eventsToShow: value } ) }
                            min={ 1 }
                            max={ 99 }
                        />
                    </PanelRow>
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

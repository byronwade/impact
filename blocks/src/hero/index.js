/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps, RichText } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";

import "./style-index.css";
import "./index.css";

/**
 * Internal dependencies
 */
import metadata from "./block.json";

/**
 * Define a custom SVG icon for the block. This icon will appear in
 * the Inserter and when the user selects the block in the Editor.
 */
const calendarIcon = (
	<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm.5 16c0 .3-.2.5-.5.5H5c-.3 0-.5-.2-.5-.5V7h15v12zM9 10H7v2h2v-2zm0 4H7v2h2v-2zm4-4h-2v2h2v-2zm4 0h-2v2h2v-2zm-4 4h-2v2h2v-2zm4 0h-2v2h2v-2z"></path>
	</svg>
);

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType(metadata.name, {
	icon: calendarIcon,
	/**
	 * @see ./edit.js
	 */
	edit: ({ attributes, setAttributes }) => {
		const { heading, subheading, primaryCtaText, primaryCtaLink, secondaryCtaText, secondaryCtaLink } = attributes;
		const blockProps = useBlockProps();

		return (
			<div {...blockProps}>
				<div className="container">
					<div className="content">
						<RichText tagName="h1" value={heading} onChange={(content) => setAttributes({ heading: content })} placeholder={__("Enter heading...", "wades")} />
						<RichText tagName="p" value={subheading} onChange={(content) => setAttributes({ subheading: content })} placeholder={__("Enter subheading...", "wades")} />
						<div className="cta-buttons">
							<RichText tagName="a" className="primary-cta" value={primaryCtaText} onChange={(content) => setAttributes({ primaryCtaText: content })} placeholder={__("Primary Button", "wades")} />
							<RichText tagName="a" className="secondary-cta" value={secondaryCtaText} onChange={(content) => setAttributes({ secondaryCtaText: content })} placeholder={__("Secondary Button", "wades")} />
						</div>
					</div>
				</div>
			</div>
		);
	},
	/**
	 * @see ./save.js
	 */
	save: ({ attributes }) => {
		const { heading, subheading, primaryCtaText, primaryCtaLink, secondaryCtaText, secondaryCtaLink } = attributes;
		const blockProps = useBlockProps.save();

		return (
			<div {...blockProps}>
				<div className="container">
					<div className="content">
						<RichText.Content tagName="h1" value={heading} />
						<RichText.Content tagName="p" value={subheading} />
						<div className="cta-buttons">
							{primaryCtaText && <RichText.Content tagName="a" className="primary-cta" value={primaryCtaText} href={primaryCtaLink} />}
							{secondaryCtaText && <RichText.Content tagName="a" className="secondary-cta" value={secondaryCtaText} href={secondaryCtaLink} />}
						</div>
					</div>
				</div>
			</div>
		);
	},
});

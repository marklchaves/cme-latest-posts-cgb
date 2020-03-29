/**
 * BLOCK: mlc12-rock-and-roll
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import "./editor.scss";
import "./style.scss";

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

import ServerSideRender from '@wordpress/server-side-render';

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType("cgb/block-mlc12-rock-and-roll", {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __("mlc12-rock-and-roll - CGB Block"), // Block title.
	icon: "megaphone", // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: "widgets", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__("mlc12-rock-and-roll — CGB Block"),
		__("CGB Example"),
		__("create-guten-block")
	],

	// Live render in editor.
	edit: function(props) {
		return (
			<ServerSideRender
				block="cgb/block-mlc12-rock-and-roll"
				attributes={props.attributes}
			/>
		);
	},

	/* Original implementation.
	edit: withSelect(select => {
		return {
			posts: select("core").getEntityRecords("postType", "post")
		};
	})(({ posts, className }) => {
		if (!posts) {
			return "Loading...";
		}

		if (posts && posts.length === 0) {
			return "No posts";
		}

		const post = posts[0];

		return (
			<a className={className} href={post.link}>
				{post.title.rendered}
			</a>
		);
	})
	*/
});

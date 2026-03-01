<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
    return;
?>

<?php $th_comments_count = $post->comment_count;
if( $th_comments_count > 0){ ?>

    <div class="blog-comments" id="comments">

            <div class="blog-comments-title">
                <h2><?php esc_html_e( 'Comments' , 'artday' ); ?></h2>     
                <span class="ws-separator"></span>           
            </div>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'callback' => 'woss_mytheme_comment',
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 80,
            ) );
            ?>
        </ol><!-- .comment-list -->

        <?php
             // Are there comments to navigate through?
            if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
                <nav class="navigation comment-navigation" role="navigation">
                    <div class="nav-previous"><?php previous_comments_link( esc_attr__( '&larr; Older Comments', 'artday' ) ); ?></div>
                    <div class="nav-next"><?php next_comments_link( esc_attr__( 'Newer Comments &rarr;', 'artday' ) ); ?></div>
                </nav><!-- .comment-navigation -->
            <?php endif; // Check for comment navigation ?>

            <?php if ( ! comments_open() && get_comments_number() ) : ?>
                <p class="no-comments"><?php esc_html_e( 'Comments are closed.' , 'artday' ); ?></p>
            <?php endif; ?>
    </div>

<?php } ?>

<div class="row">
	<div class="col-sm-12">
		<div class="ws-leave-comment">
			<?php
			$commenter = wp_get_current_commenter();
			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );
			$comment_args = array(
				//'title_reply'=> wp_kses_post(__('Leave a comment <span class="ws-separator"></span>', 'artday'),
                'title_reply'  =>  wp_kses_post(__( 'Leave a Reply'  ).'<span class="ws-separator"></span>'),
				'fields' => apply_filters( 'comment_form_default_fields', array(
					'author' => '
						<div class="row">
							<div class="col-sm-4 name-input">
								<label for="name">' . esc_html__( 'Name' ) . ' <span class="required">*</span></label>
								<input type="text" name="author" class="input-md form-control" maxlength="100" id="name" value="' . esc_attr( $commenter['comment_author'] ) . '" '.$aria_req.' />
							</div>
					',
					'email' => '
							<div class="col-sm-4 email-input">
								<label for="mail">' . esc_html__('Email' ) . ' <span class="required">*</span></label>
								<input id="mail" name="email" class="form-control" maxlength="100" type="text" value="' . sanitize_email(  $commenter['comment_author_email'] ) . '" '.$aria_req.' />
							</div>
						
						
					',
					'url' => '
							<div class="col-sm-4 website-input">
								<label for="url">' . esc_html__( 'Website' ) . '</label>
								<input id="url" name="url"  class="form-control" maxlength="100" type="text" value="' . esc_url( $commenter['comment_author_url'] ) . '"  />
							</div>
						</div>	
					'
				)),
				'comment_field' => 
						'<div class="row">
							<div class="col-sm-12 comment-area">
								<label for="comment">' . esc_html_x( 'Comment', 'noun' ) . '</label>
								<textarea cols="45" rows="8" id="comment" tabindex="4" name="comment" aria-required="true"></textarea>							
							</div>
						</div>',
						
				'comment_notes_before' => '',
				'comment_notes_after' => '',
			);
			?>
			<?php global $post; ?>
			<?php if('open' == $post->comment_status){ ?>
				<?php comment_form($comment_args); ?>
			<?php } ?>
		</div>
	</div>
</div>


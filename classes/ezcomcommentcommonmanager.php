<?php
class ezcomCommentCommonManager extends ezcomCommentManager
{ 
    public function beforeAddingComment( $comment, $user )
    {
        return true;
    }
    
    public function validateInput( $comment )
    {
        if( is_null( $comment ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Parameter is empty!' );
        }
        if( $comment->attribute( 'name' ) == '' )
        {
            return ezi18n( 'comment/view/validateInput', 'Name is empty!' );
        }
        if( $comment->attribute( 'email' ) == '' )
        {
            return ezi18n( 'comment/view/validateInput', 'Email is empty!' );
        }
        else
        {   
            if( eZMail::validate( $comment->attribute( 'email' ) ) == false )
            {
                return ezi18n( 'comment/view/validateInput', 'Not a valid email address!' );
            }
        }
        if( $comment->attribute( 'text' ) == '' )
        {
            return ezi18n( 'comment/view/validateInput', 'Content is empty!' );
        }
        if ( $comment->attribute( 'language_id' ) == '' || !is_numeric( $comment->attribute( 'language_id' ) ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Language is empty or not int!' );
        }
        if ( $comment->attribute( 'contentobject_id' ) == '' || !is_numeric( $comment->attribute( 'contentobject_id' ) ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Object ID can not be empty or string!' );
        }
        return true;
    }
}
?>
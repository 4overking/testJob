$('#showCommentForm')
    .click(function(e){
        e.preventDefault();
        $('#comment-modal-window').load($(this).attr('data-url'), function() {
            $('#comment-modal').modal('toggle');
        });
    });

$('#comment-submit').click(function () {
    var $form = $('#comment-modal').find('form');
    var request = $.post( $form.attr('action'), $form.serialize());
    request.done(function( data ) {
        $commetsWrapper = $('#commets-wrapper');
        $commetsWrapperChildrens = $commetsWrapper.children();
        console.log($commetsWrapperChildrens);
        if($commetsWrapperChildrens.length > 0){
            $commetsWrapperChildrens.first().before(data);
        } else {
            $commetsWrapper.html(data);
        }
        $('#comment-modal').modal('toggle');
    });

    request.fail(function( jqXHR ) {
        if(jqXHR.status == 400){
            $('#comment-modal-window').html(jqXHR.responseText)
        } else {
            alert('We have problems at this moment. please retry later!');
        }
    });
});

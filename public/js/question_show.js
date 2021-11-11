$(document).ready(function(){

    let $container = $('.js-vote-arrows');
    console.log($container);
    $container.find('a').on('click',function (e) {
        console.log(e);
        e.preventDefault();
        let $link = $(e.currentTarget);

        $.ajax({
            url: '/comments/10/vote/'+ $link.data('direction'),
            method:'POST'
        }).then(function(data){
            $container.find('.js-vote-total').text(data.votes);
        })

    });
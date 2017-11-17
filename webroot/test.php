<a id="add" href="#">Цена 1500грн</a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

<script>
    $('#add').hover(function() {
        $('#add').hover(function(){
            $(this).text('Цена 1350грн');
            $(this).css("color", 'red');
            $(this).css("font-size", '150%');
        },function(){
            $(this).text('Цена 1500грн');
            $(this).css("color", 'black');
            $(this).css("font-size", '100%');
        });
    });
</script>



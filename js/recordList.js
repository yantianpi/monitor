$(document).ready(function () { 
    $('.click').click(function(){ 
        var id = $(this).attr('id')
        $.ajax({
            type: "GET",
            url: "/set/recordTest.php",
            data: "id="+id,
            async : false, 
            success: function(data){
                $('#mess').html(data);
                $('#myModal').modal('show');
            }
        });
    });
}); 
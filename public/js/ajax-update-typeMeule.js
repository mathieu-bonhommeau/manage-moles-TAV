//This file is for adapt position list hen a machine is choose on add/edit mole page

$(document).ready(function(){
    
    $(".edit-type-meule").on("click", function(event) {
        
        //We retrieve id of the choice list machine
        let idTypeMeule = $(this).attr('id');

        //split function for explode string of characters and return an array 
        let strToArray = idTypeMeule.split('-')

        //'.get(-1)' for retrieve the last element of this array
        let id = $(strToArray).get(-1);

        //'.get(0)' for retrieve the first element of this array
        let cuName = $(strToArray).get(0);

        $.ajax({
            url: "/edit/cu/" + cuName,
            type: "POST",
            data: 'id=' + id,
            dataType: "json",
            async: true,

            success: function(data, status) {
                $('#update-typeMeule' + id).find('.modal-body').append(data.content);
                /*
                let positionOption = '';
                $('.position-list').remove();
                let i = 0;
                for (let position of data) {
                    positionOption = positionOption + '<option value="' + position.name + '"class="position-list">' + position.name + '</option>';
                    i++;
                }
                $('#' + idPositionList).append(positionOption);
                */
            }
        })
        
    })
});
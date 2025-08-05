/**
 * 2024 (c) Egio digital
 *
 * MODULE egblockcategory
 *
 * @author    Egio digital
 * @copyright Copyright (c) , Egio digital
 * @license   Commercial
 * @version    1.0.0
 */


$(document).ready(function() {

    $(".visualiser_link_rewrite").hide();
    $("#table-egblockbuilder").find('tr').each(function() {
        var $row = $(this);
        var id = $row.find('.column-id_egblockbuilder').text().trim();
        var link_rewrite = $row.find('.visualiser_link_rewrite').text().trim();
        
        if (id && link_rewrite) {
            var url = base_url +'gender/'+ id + '-' + link_rewrite;
            var link = '<a href="' + url + '" target="_blank">' + url + '</a>';
            $row.find('.column-visualiser').html(link);
        }
    });
    var id_egblockbuilder_items = $("#id_egblockbuilder_items").val();
    var firstOption = $('#productIds option').eq(0); // Get the option based on the index
    var firstValue = firstOption.val(); // Get the value of the selected option
    var firstText = firstOption.text();
    $("#add_product_arrive").attr('data-id',firstValue);
    $("#add_product_arrive").attr('data-title',firstText);
    
    $(document).on('click', '.active-result', function() {
        var index =  $(this).data('option-array-index'); // Alert when an active-result is clicked
        var selectedOption = $('#productIds option').eq(index); // Get the option based on the index
        var selectedValue = selectedOption.val(); // Get the value of the selected option
        var selectedText = selectedOption.text()
        $("#add_product_arrive").attr('data-id',selectedValue);
        $("#add_product_arrive").attr('data-title',selectedText);
    });

    // Function to add a new row
    function addNewRow(id, produit) {
        var newRow = `
            <tr id="${id}" class="odd">
                <td class="pointer">
                    <div class="position-drag-gallery">
                        <i class="material-icons">drag_indicator</i>
                    </div>
                </td>
                <td class="pointer">${id}</td>
                <td class="pointer">${produit}</td>
                <td class="pointer">
                    <div class="btn-group-action">
                        <div class="btn-group">
                            <a href="#" data-egid="${id}" class="btn tooltip-link product-edit link-icon-delete delete-arrive" data-toggle="pstooltip" title="" data-placement="right" data-original-title="Supprimer définitivement cette item.">
                                <i class="material-icons">delete</i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        `;
        // Append the new row to the tbody
        $('.selected_products_row_position').append(newRow);
    }
    
    // Get the chosen products
    var chosenProducts = $('#chosen_products').val();
    if (chosenProducts) {
        var chosenProductsArray = chosenProducts.split(',');
        chosenProductsArray.forEach(function(id) {
            var option = $('#productIds').find('option[value="' + id + '"]');
            if (option.length) {
                var produit = option.text();
                addNewRow(id, produit);
            }
        });
    }
    $('#add_product_arrive').on('click', function() {
      
      var id = $(this).attr('data-id');
      var produit  = $(this).attr('data-title');
              // Create a new row
              var newRow = `
              <tr id="${id}" class="odd">
                  <td class="pointer">
                      <div class="position-drag-gallery">
                          <i class="material-icons">drag_indicator</i>
                      </div>
                  </td>
                  <td class="pointer">${id}</td>
                  <td class="pointer">${produit}</td>
                  <td class="pointer">
                      <div class="btn-group-action">
                          <div class="btn-group">
                              <a href="#" data-egid="${id}"  class="btn tooltip-link product-edit link-icon-delete delete-arrive" data-toggle="pstooltip" title="" data-placement="right" data-original-title="Supprimer définitivement cette item.">
                                  <i class="material-icons">delete</i>
                              </a>
                          </div>
                      </div>
                  </td>
              </tr>
          `;
  
          // Append the new row to the tbody
          
            
            // Update the chosen_products input field
            var chosenProducts = $('#chosen_products').val();
            var chosenProductsArray = chosenProducts ? chosenProducts.split(',') : [];

            // Check if the id already exists in the array
            if (!chosenProductsArray.includes(id)) {
                chosenProductsArray.push(id);
                $('.selected_products_row_position').append(newRow);
            }else {
                alert('Produit déja exist dans votre liste');
            }
            
            // Update the chosen_products input field value
            $('#chosen_products').val(chosenProductsArray.join(','));
            if ($('#id_egblockbuilder_items').length) {
                var id_egblockbuilder_items = $('#id_egblockbuilder_items').val();
                var chosen_products = $('#chosen_products').val();
           
                if (chosen_products) {
                    $.ajax({
                        type: 'POST',
                        url: EgBlockBuilderItemsController + '&action=saveEgDetailsArrive&ajax=true',
                        data: { 
                            id_egblockbuilder_items: id_egblockbuilder_items,
                            chosen_products: chosen_products
                        },
                        dataType: 'json', 
                        success: function (response) { 
                            $('html, body').animate({
                                scrollTop: $("#active_on").offset().top  
                            }, 'slow'); 
                        },
                        error: function () { 
                        }
                    });
                }  
            }  

    });
    
    $(document).on('click', '.delete-arrive', function() { 
        $(this).parent().parent().parent().parent().remove();

        var deleteId = $(this).data('egid'); 

        // Remove the id from the chosen_products input field
        var currentProducts = $('#chosen_products').val().split(',');
        var updatedProducts = currentProducts.filter(product => product !== deleteId.toString());

        // Update the chosen_products input field value
        $('#chosen_products').val(updatedProducts.join(','));
        if ($('#id_egblockbuilder_items').length) {
            var id_egblockbuilder_items = $('#id_egblockbuilder_items').val();
            var chosen_products = $('#chosen_products').val();
       
            if (chosen_products) {
                $.ajax({
                    type: 'POST',
                    url: EgBlockBuilderItemsController + '&action=saveEgDetailsArrive&ajax=true',
                    data: { 
                        id_egblockbuilder_items: id_egblockbuilder_items,
                        chosen_products: chosen_products
                    },
                    dataType: 'json', 
                    success: function (response) { 
                        // Optionally, handle the response here if needed
                    },
                    error: function () { 
                    }
                });
            }  
        }
    });
    // Drag And drop EG Marque
        $(".selected_products_row_position").sortable({
        cursor: "move",
        placeholder: "ui-sortable-placeholder",
        delay: 150,
        stop: function () {
            var selectedData = new Array();
            $('.selected_products_row_position>tr').each(function () {
                selectedData.push($(this).attr("id"));
            });
            updateItemDetailsMarqueOrder(selectedData);
        }
    });
    if ($("#id_egblockbuilder_items").length){ 
        $("#type").attr('disabled','true');
    }
    $("#table-egblockbuilder_items tbody").find('tr').each(function() {
        // Get the type value from the column-type cell
        var typeValue = $(this).find('.column-type').text().trim();
         $('#table-egblockbuilder_items tr.nodrag.nodrop th:nth-child(9)').hide();
         $(this).find('.column-type').hide();
        // Show only the column-title_* cell that matches the type value
        for (var i = 1; i <= 6; i++) {

            if (typeValue == i) {
                $(this).find('.column-title_' + i).show();
                $('#table-egblockbuilder_items tr.nodrag.nodrop th:nth-child(' + (i+2) + ')').show();
            } else {
                $(this).find('.column-title_' + i).hide();
                $('#table-egblockbuilder_items tr.nodrag.nodrop th:nth-child(' + (i+2) + ')').hide();
            }
                
        } 
    });
    function toggleFields() {
        var selectedValue = $('#type').val();
        if (selectedValue == 1) {
            $('#text_button'+"_"+id_lang).closest('.form-group').show();
            $('#link_button'+"_"+id_lang).closest('.form-group').show(); 
            
        } else {
            $('#text_button'+"_"+id_lang).closest('.form-group').hide();
            $('#link_button'+"_"+id_lang).closest('.form-group').hide(); 
        }
        if (selectedValue == 2) { 
            $('#nb_produit').closest('.form-group').show();
            $('#productIds').closest('.form-group').show();
            $('#selected_products').closest('.form-group').show(); 
            $('#url_dernier').closest('.form-group').show();
            $('.line_products').closest('.form-group').show();
            
        } else { 
            $('#nb_produit').closest('.form-group').hide();
            $('#productIds').closest('.form-group').hide();
            $('#selected_products').closest('.form-group').hide();
            $('#link_marque').closest('.form-group').hide();
            $('#url_dernier').closest('.form-group').hide();
            $('.line_products').closest('.form-group').hide();
        }
        if (selectedValue == 3) {  
            $('#title_marque').closest('.form-group').show();
            $('#image_marque').parent().parent().parent().parent().show();  
            $('#added_marques').closest('.form-group').show();
            $('#link_marque').closest('.form-group').show();
            $('.line_marque').closest('.form-group').show();
            
        } else {  

            $('#title_marque').closest('.form-group').hide();
            $('#image_marque').parent().parent().parent().parent().hide();  
            $('#added_marques').closest('.form-group').hide();
            $('#link_marque').closest('.form-group').hide();
            $('.line_marque').closest('.form-group').hide();
        }
        if (selectedValue == 4) { 
            $("#egblockbuilder_items_form").find('#categories-tree').closest('.form-group').show();
            $('#title_sac').closest('.form-group').show();
            $('#banner').parent().parent().parent().parent().show();  
            $('#banner_mobile').parent().parent().parent().parent().show();  
            $('#added_sacs').closest('.form-group').show();
            $('.line_sac').closest('.form-group').show();
            
        } else { 
            $('#title_sac').closest('.form-group').hide();
            $('#banner').parent().parent().parent().parent().hide();
            $('#banner_mobile').parent().parent().parent().parent().hide();  
            $('#added_sacs').closest('.form-group').hide();
            $("#egblockbuilder_items_form").find('#categories-tree').closest('.form-group').hide();
            $('.line_sac').closest('.form-group').hide();
        }
        if (selectedValue == 5) { 
             
            $('#title_moment').closest('.form-group').show();
            $('#link_moment').closest('.form-group').show();
            $('#image_moment').parent().parent().parent().parent().show();  
            $('#added_moment').closest('.form-group').show();
            $('.line_moment').closest('.form-group').show();
        } else { 
            $('#title_moment').closest('.form-group').hide();
            $('#link_moment').closest('.form-group').hide();
            $('#image_moment').parent().parent().parent().parent().hide();  
            $('#added_moment').closest('.form-group').hide();
            $('.line_moment').closest('.form-group').hide();
            
        }
 
    }
    function toggleInputs() {

        var selectedValue = $('#type').val();
        $('textarea[id^="title_"]').closest('.form-group').hide();
        $('#title_' + selectedValue+"_"+id_lang).closest('.form-group').show();
    }
        // Initial toggle
        toggleFields();
        toggleInputs();

        $('#type').change(function () {
            toggleInputs();
            toggleFields();
        });
        $('#type').val($('#type').val()).change();

        // --------------------- get  all  marques --------------------------
        
        getAllmarques(id_egblockbuilder_items);
        // ----------------------------Save EG Marque------------------------
        $('#add_marque').on('click', function (e) {
            e.preventDefault();
            
            var id_egblockbuilder_items = $("#id_egblockbuilder_items").val();
            var id_egblockbuilder_marques = $("#id_egblockbuilder_marques").val();
            
            var formData = new FormData();
            if (id_egblockbuilder_items) { 
                formData.append("id_egblockbuilder_items", id_egblockbuilder_items);
            }
            if (id_egblockbuilder_marques) { 
                formData.append("id_egblockbuilder_marques", id_egblockbuilder_marques);
            }
            
            var link_marque = $("#link_marque").val();
            if (link_marque) { 
                formData.append("link_marque", link_marque);
            }
            
            var iframe = $('#title_marque_ifr');
            if (iframe.length) {
                // Access the body of the iframe
                var iframeBody = iframe.contents().find('body'); 
                // Get the HTML content inside the body
                var title_marque = iframeBody.html(); 
                formData.append("title_marque", title_marque);
            } 
            
            var image_marque = $("#image_marque")[0].files[0]; // Get the file
            if (image_marque) {
                formData.append("image_marque", image_marque); // Append the file
            }
            var image_marque = typeof image_marque !== 'undefined' ? image_marque : '';
            var title_marque = typeof title_marque !== 'undefined' ? title_marque : '';
            var link_marque = typeof link_marque !== 'undefined' ? link_marque : '';
            if ((image_marque && image_marque.length > 0) || 
            (title_marque && title_marque.length > 0 && title_marque != '<p><br data-mce-bogus="1"></p>') || 
            (link_marque && link_marque.length > 0)) { 

            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=saveEgDetailsMarque&ajax=true',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    for (let index = 0; index < response.length; index++) {
                        const EgDetailsMarque = response[index];
                        
                        if (EgDetailsMarque) {
                            var classe, content, status, $active_text;
        
                            if (EgDetailsMarque.active == 0) {
                                classe = "eg-action-disabled";
                                content = "clear";
                                status = 1;
                            } else {
                                classe = "eg-action-enabled";
                                content = "check";
                                status = 0;
                            }
        
                            $active_text = EgDetailsMarque.active == 1 ? enable : disable;
        
                            if (id_egblockbuilder_marques) {
                                // Update existing row
                                var $row = $('tr[data-egid="' + id_egblockbuilder_marques + '"]');
                                $row.find('td:eq(1)').text(EgDetailsMarque.id_egblockbuilder_marques);
                                $row.find('td:eq(2)').html(EgDetailsMarque.title_marque); 
                                $row.find('td:eq(3)').text(EgDetailsMarque.link_marque); 
                                var imm = '<img data-id="'+EgDetailsMarque.image_marque+'" width="100" heigth="100" src="'+src_rac+EgDetailsMarque.image_marque+'" >';
                                $row.find('td:eq(4)').html(imm); 
                                var upd = "updateEgItemDetailsMarque("+EgDetailsMarque.id_egblockbuilder_marques+",'"+EgDetailsMarque.image_marque+"'); return false;";
                                
                                $row.find('td:eq(6)').find('.marque-edit').attr("onclick",upd); 
                                $row.find('.details_marque_link_icon_active')
                                    .attr('data-status', status)
                                    .data('status', status)
                                    .find('i')
                                    .removeClass('eg-action-enabled eg-action-disabled')
                                    .addClass(classe)
                                    .text(content);
                                $('#id_egblockbuilder_marques').remove();   
                                iframeBody.html('');   
                                $("#link_marque").val('');
                                $("#add_marque").find("i").text('add_circle');
                                showSuccessMessage("Update successful");
                                $('html, body').animate({
                                    scrollTop: $("#active_on").offset().top  
                                }, 'slow'); 
                            } else {
                                // Add new row
                                var imm = '<img data-id="'+EgDetailsMarque.image_marque+'"  width="100" heigth="100" src="'+src_rac+EgDetailsMarque.image_marque+'" >';
                                    
                                var addNewRowItemMarque = '<tr id="' + EgDetailsMarque.id_egblockbuilder_marques + 
                                    '" data-egid="' + EgDetailsMarque.id_egblockbuilder_marques + 
                                    '" data-title="' + EgDetailsMarque.title_marque + '" class="odd">\n' +
                                    '<td class="pointer" onclick="">\n' +
                                    ' <div class="position-drag-gallery">\n' +
                                    '<i class="material-icons">drag_indicator</i>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '<td class="pointer" onclick="">' + EgDetailsMarque.id_egblockbuilder_marques + '</td>\n' +
                                    '<td class="pointer" onclick="">' + EgDetailsMarque.title_marque + '</td>\n' + 
                                    '<td class="pointer" onclick="">' + EgDetailsMarque.link_marque + '</td>\n' + 
                                    '<td class="pointer" onclick="">' + imm + '</td>\n' + 
                                    '<td class="pointer" onclick="">\n' +
                                    '<div id="">\n' +
                                    '<a class="details_marque_link_icon_active btn" data-status="' + status + '" data-egid="' + EgDetailsMarque.id_egblockbuilder_marques + '" data-title="' + EgDetailsMarque.title_marque + '">\n' +
                                    '<i class="material-icons ' + classe + '">' + content + '</i>\n' +
                                    '</a>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '<td class="pointer" onclick="">\n' +
                                    '<div class="btn-group-action">\n' +
                                    '<div class="btn-group">\n' +
                                    '<a href="" data-egid="' + EgDetailsMarque.id_egblockbuilder_marques + 
                                    '" data-title="' + EgDetailsMarque.title_marque +
                                    '" data-link="' + EgDetailsMarque.link_marque + '" title="" class="btn tooltip-link marque-edit link-icon-delete">\n' +
                                    '<i class="material-icons">edit</i>\n' +
                                    '</a>\n' +
                                    '</div>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '<td class="pointer" onclick="">\n' +
                                    '<div class="btn-group-action">\n' +
                                    '<div class="btn-group">\n' +
                                    '<a href="" data-egid="' + EgDetailsMarque.id_egblockbuilder_marques + '" data-title="' + EgDetailsMarque.title_marque + '" data-link="' + EgDetailsMarque.link_marque + '" title="" class="btn tooltip-link link-icon-delete delete-marque">\n' +
                                    '<i class="material-icons">delete</i>\n' +
                                    '</a>\n' +
                                    '</div>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '</tr>';
                                    
                                $('.added_marques_row_position').append(addNewRowItemMarque);
                                $('#id_egblockbuilder_marques').remove();   
                                iframeBody.html('');   
                                $("#link_marque").val('');
                                $("#add_marque").find("i").text('add_circle');
                                showSuccessMessage("Update successful");
                                $('html, body').animate({
                                    scrollTop: $("#active_on").offset().top  
                                }, 'slow'); 
                            }
                        }
                    }
                }
            });
            } else {
                alert("Remplissez au moins un champs pour ajouté");
            }
        });
        
        $(document).on('click', '.details_marque_link_icon_active', function(e) { 
            
            egid = $(this).attr('data-egid');
            egstatus = $(this).attr('data-status');
            updateStatusEgDetailsMarque(this, egid, egstatus);
        });
        $(document).on('click', '.marque-edit', function(e) { 
            e.preventDefault(); 
        
            var id_egblockbuilder_marques = $(this).attr('data-egid');
            var title_marque = $(this).attr('data-title');
            var link_marque = $(this).attr('data-link');
            
            $("#link_marque").val(link_marque);
        
            // Clear any previously added hidden input
            $('#egblockbuilder_items_form input[name="id_egblockbuilder_marques"]').remove();
        
            // Append hidden input with id_egblockbuilder_marques
            $('#egblockbuilder_items_form').append('<input type="hidden" id="id_egblockbuilder_marques" name="id_egblockbuilder_marques" value="'+id_egblockbuilder_marques+'">');
        
            // Fill the input fields with the existing values from the row
            var iframe = $('#title_marque_ifr');
            if (iframe.length) {
                // Access the body of the iframe
                var iframeBody = iframe.contents().find('body');
                // Get the HTML content inside the body
                iframeBody.html(title_marque);
            }
            // Scroll to the top of the iframe
            $('html, body').animate({
                scrollTop: $("#type").offset().top
            }, 'slow');
            $("#add_marque").find("i").text('save');
        });
        
        $(document).on('click', '.delete-marque', function(e) { 
            e.preventDefault(); 
            id_egblockbuilder_marques = $(this).attr('data-egid');
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=deleteEgItemDetailsMarque&ajax=true&id_egblockbuilder_marques=' + id_egblockbuilder_marques,
                headers: {"cache-control": "no-cache"},
                cache: false,
                dataType: 'json',
                data:{id_egblockbuilder_marques:id_egblockbuilder_marques},
                success:function(response) { 
                    if(response == 'success') {
                        selector = '.link-icon-delete[data-egid="' + id_egblockbuilder_marques + '"]';
                        $(selector).parents('tr').hide();
                        showSuccessMessage("Update successful");
                    }
                }
            });
        });

        // Drag And drop EG Marque
        $(".added_marques_row_position").sortable({
            cursor: "move",
            placeholder: "ui-sortable-placeholder",
            delay: 150,
            stop: function () {
                var selectedData = new Array();
                $('.added_marques_row_position>tr').each(function () {
                    selectedData.push($(this).attr("id"));
                });
                updateItemDetailsMarqueOrder(selectedData);
            }
        });
  
        
        // ----------------------------END EG Marque ------------------------
        // Manage Status EG Item Marque
        function updateStatusEgDetailsMarque(elm, id_egblockbuilder_marques, status)
        {
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=updateStatusEgDetailsMarque&ajax=true&id_egblockbuilder_marques=' + id_egblockbuilder_marques + '&status=' + status,
                contentType: false,
                processData: false,
                success:function(response) {  
                    if(response == 1) {  
                        if (status == 0) {
                            $(elm).removeClass('eg-action-enabled');
                            $(elm).addClass('eg-action-disabled');
                            $(elm).find('i').text('clear');
                            $(elm).attr("data-status", 1);
                        } else {
                            $(elm).removeClass('eg-action-disabled');
                            $(elm).addClass('eg-action-enabled');
                            $(elm).find('i').text('check');
                            $(elm).attr("data-status", 0);
                        }
                    }
                    showSuccessMessage("Update successful");
                }
            });
        }

        // Update EG Item Marque 
        function openEgDetailsMarque(url)
        {
            var controllerUrl = url;
            $.fancybox.open({
                'href'      : controllerUrl,
                'type'      : 'iframe',
                'maxWidth'  : 900,
                'maxHeight' : 720,
                'afterClose':function () {
                    window.location.reload();
                },
            });
        }

         

        // Update EG Item Marque
        function updateEgItemDetailsMarque(id_egblockbuilder_marques, image_marque)
        {
            // Clear any previously added hidden input
            $('#eg_details_item input[name="id_egblockbuilder_marques"]').remove();
            
            // Append hidden input with id_egblockbuilder_marques
            $('#eg_details_item').append('<input type="hidden" id="id_egblockbuilder_marques" name="id_egblockbuilder_marques" value="'+id_egblockbuilder_marques+'">');
            
            // Get the row corresponding to id_egblockbuilder_marques
            var $row = $('tr[data-egid="' + id_egblockbuilder_marques + '"]');
            
            // Fill the input fields with the existing values from the row
            var title = $row.find('td:eq(2)').text(); 
            var image_marque = $row.find('td:eq(3)').find('img').attr('src'); 
            $("#title_marque").val(title); 
            $('#marque_image').val(image_marque);
            
            return false; 
        }

        // Edit Order EG Item Marque
        function updateItemDetailsMarqueOrder(data) {
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=updateItemDetailsMarqueOrder&ajax=true',
                headers: {"cache-control": "no-cache"},
                cache: false,
                dataType: 'json',
                data:{ids:data},
                success:function(response){
                    if(response == 'success') {
                        showSuccessMessage("Update successful");
                    }
                }
            });
        }
        // Edit Order EG Item Marque
        function getAllmarques(id_egblockbuilder_items) {
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=AllEgDetailsMarque&ajax=true',
                headers: {"cache-control": "no-cache"},
                cache: false,
                dataType: 'json',
                data: {id_egblockbuilder_items: id_egblockbuilder_items},
                success: function(response) {
                    for (let index = 0; index < response.length; index++) {
                        const EgDetailsMarque = response[index];
                        
                        if (EgDetailsMarque) {
                            var classe, content, status, $active_text;
        
                            if (EgDetailsMarque.active == 0) {
                                classe = "eg-action-disabled";
                                content = "clear";
                                status = 1;
                            } else {
                                classe = "eg-action-enabled";
                                content = "check";
                                status = 0;
                            }
        
                            $active_text = EgDetailsMarque.active == 1 ? enable : disable;
        
                            // Add new row
                            var imm = '<img data-id="'+EgDetailsMarque.image_marque+'" width="100" heigth="100" src="'+src_rac+EgDetailsMarque.image_marque+'" >';
                                
                            var addNewRowItemMarque = '<tr id="' + EgDetailsMarque.id_egblockbuilder_marques + '" data-egid="' + EgDetailsMarque.id_egblockbuilder_marques + '" data-title="' + EgDetailsMarque.title_marque + '" data-img="' + EgDetailsMarque.image_marque + '" class="odd">\n' +
                                '<td class="pointer" onclick="">\n' +
                                ' <div class="position-drag-gallery">\n' +
                                '<i class="material-icons">drag_indicator</i>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '<td class="pointer" onclick="">' + EgDetailsMarque.id_egblockbuilder_marques + '</td>\n' +
                                '<td class="pointer" onclick="">' + EgDetailsMarque.title_marque + '</td>\n' +
                                '<td class="pointer" onclick="">' + EgDetailsMarque.link_marque + '</td>\n' +
                                '<td class="pointer" onclick="">' + imm + '</td>\n' +
                                '<td class="pointer" onclick="">\n' +
                                '<div id="">\n' +
                                '<a class="details_marque_link_icon_active btn" data-status="' + status + '" data-egid="' + EgDetailsMarque.id_egblockbuilder_marques + '" data-title="' + EgDetailsMarque.title_marque + '" data-img="' + EgDetailsMarque.image_marque + '">\n' +
                                '<i class="material-icons ' + classe + '">' + content + '</i>\n' +
                                '</a>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '<td class="pointer" onclick="">\n' +
                                '<div class="btn-group-action">\n' +
                                '<div class="btn-group">\n' +
                                '<a href="" data-egid="' + EgDetailsMarque.id_egblockbuilder_marques + '" data-title="' + EgDetailsMarque.title_marque + '" data-link="' + EgDetailsMarque.link_marque +  '" data-img="' + EgDetailsMarque.image_marque + '"  title="" class="btn tooltip-link marque-edit link-icon-delete">\n' +
                                '<i class="material-icons">edit</i>\n' +
                                '</a>\n' +
                                '</div>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '<td class="pointer" onclick="">\n' +
                                '<div class="btn-group-action">\n' +
                                '<div class="btn-group">\n' +
                                '<a href="" data-egid="' + EgDetailsMarque.id_egblockbuilder_marques + '" data-title="' + EgDetailsMarque.title_marque + '" data-img="' + EgDetailsMarque.image_marque + '"   title="" class="btn tooltip-link link-icon-delete delete-marque">\n' +
                                '<i class="material-icons">delete</i>\n' +
                                '</a>\n' +
                                '</div>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '</tr>'; 
                            $('.added_marques_row_position').append(addNewRowItemMarque);
                        }
                    }
                }
            });
        }
        
 

        /***************************  moment   ***********************/  
 
        // --------------------- get all moments --------------------------
        
        getAllMoments(id_egblockbuilder_items);

        // ----------------------------Save EG Moment------------------------
        $('#add_moment').on('click', function (e) {
            e.preventDefault();

            var id_egblockbuilder_items = $("#id_egblockbuilder_items").val();
            var id_egblockbuilder_moment = $("#id_egblockbuilder_moment").val();
            
            var formData = new FormData();
            if (id_egblockbuilder_items) { 
                formData.append("id_egblockbuilder_items", id_egblockbuilder_items);
            }
            if (id_egblockbuilder_moment) { 
                formData.append("id_egblockbuilder_moment", id_egblockbuilder_moment);
            }
            var link_moment = $("#link_moment").val();
            
            if (link_moment) { 
                formData.append("link_moment", link_moment);
            }
            var iframe = $('#title_moment_ifr');
            if (iframe.length) {
                // Access the body of the iframe
                var iframeBody = iframe.contents().find('body'); 
                // Get the HTML content inside the body
                var title_moment = iframeBody.html(); 
                formData.append("title_moment", title_moment);
            } 
            var image_moment = $("#image_moment")[0].files[0]; // Get the file
            if (image_moment) {
                formData.append("image_moment", image_moment); // Append the file
            }
            if ((image_moment && image_moment.length > 0) || 
                (title_moment && title_moment.length > 0  && title_moment != '<p><br data-mce-bogus="1"></p>') || 
                (link_moment && link_moment.length > 0)) {
                 
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=saveEgDetailsMoment&ajax=true',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    for (let index = 0; index < response.length; index++) {
                        const EgDetailsMoment = response[index];
                        if (EgDetailsMoment) {
                            var classe, content, status, $active_text;

                            if (EgDetailsMoment.active == 0) {
                                classe = "eg-action-disabled";
                                content = "clear";
                                status = 1;
                            } else {
                                classe = "eg-action-enabled";
                                content = "check";
                                status = 0;
                            }

                            $active_text = EgDetailsMoment.active == 1 ? enable : disable;

                            if (id_egblockbuilder_moment) {
                                // Update existing row
                                var $row = $('tr[data-egid="' + id_egblockbuilder_moment + '"]');
                                $row.find('td:eq(1)').text(EgDetailsMoment.id_egblockbuilder_moment);
                                $row.find('td:eq(2)').html(EgDetailsMoment.title_moment); 
                                $row.find('td:eq(3)').text(EgDetailsMoment.link_moment);
                                var imm = '<img data-id="'+EgDetailsMoment.image_moment+'" width="100" heigth="100" src="'+src_rac+EgDetailsMoment.image_moment+'" >';
                                $row.find('td:eq(4)').html(imm);  
                                var upd = "updateEgItemDetailsMoment("+EgDetailsMoment.id_egblockbuilder_moment+"); return false;";
                                
                                $row.find('td:eq(5)').find('.moment-edit').attr("onclick",upd); 
                                $row.find('.details_moment_link_icon_active')
                                    .attr('data-status', status)
                                    .data('status', status)
                                    .find('i')
                                    .removeClass('eg-action-enabled eg-action-disabled')
                                    .addClass(classe)
                                    .text(content);
                                $('#egblockbuilder_items_form input[name="id_egblockbuilder_moment"]').remove();   
                                iframeBody.html('');   
                                $("#link_moment").val('');
                                $("#add_moment").find("i").text('add_circle');
                                showSuccessMessage("Update successful");
                                $('html, body').animate({
                                    scrollTop: $("#active_on").offset().top  
                                }, 'slow'); 
                            } else {
                                // Add new row
                                var imm = '<img data-id="'+EgDetailsMoment.image_moment+'" width="100" heigth="100" src="'+src_rac+EgDetailsMoment.image_moment+'" >';
                                
                                var addNewRowItemMoment = '<tr id="' + EgDetailsMoment.id_egblockbuilder_moment + 
                                    '" data-egid="' + EgDetailsMoment.id_egblockbuilder_moment + 
                                    '" data-title="' + EgDetailsMoment.title_moment + '" class="odd">\n' +
                                    '<td class="pointer" onclick="">\n' +
                                    ' <div class="position-drag-gallery">\n' +
                                    '<i class="material-icons">drag_indicator</i>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '<td class="pointer" onclick="">' + EgDetailsMoment.id_egblockbuilder_moment + '</td>\n' +
                                    '<td class="pointer" onclick="">' + EgDetailsMoment.title_moment + '</td>\n' + 
                                    '<td class="pointer" onclick="">' + EgDetailsMoment.link_moment + '</td>\n' + 
                                    '<td class="pointer" onclick="">' + imm + '</td>\n' + 
                                    '<td class="pointer" onclick="">\n' +
                                    '<div id="">\n' +
                                    '<a class="details_moment_link_icon_active btn" data-status="' + status + '" data-egid="' + EgDetailsMoment.id_egblockbuilder_moment + '" data-title="' + EgDetailsMoment.title_moment + '">\n' +
                                    '<i class="material-icons ' + classe + '">' + content + '</i>\n' +
                                    '</a>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '<td class="pointer" onclick="">\n' +
                                    '<div class="btn-group-action">\n' +
                                    '<div class="btn-group">\n' +
                                    '<a href="" data-egid="' + EgDetailsMoment.id_egblockbuilder_moment + 
                                    '" data-title="' + EgDetailsMoment.title_moment +
                                    '" data-link="' + EgDetailsMoment.link_moment + '" title="" class="btn tooltip-link moment-edit link-icon-delete">\n' +
                                    '<i class="material-icons">edit</i>\n' +
                                    '</a>\n' +
                                    '</div>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '<td class="pointer" onclick="">\n' +
                                    '<div class="btn-group-action">\n' +
                                    '<div class="btn-group">\n' +
                                    '<a href="" data-egid="' + EgDetailsMoment.id_egblockbuilder_moment + '" data-title="' + EgDetailsMoment.title_moment + '" data-link="' + EgDetailsMoment.link_moment + '" title="" class="btn tooltip-link link-icon-delete delete-moment">\n' +
                                    '<i class="material-icons">delete</i>\n' +
                                    '</a>\n' +
                                    '</div>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '</tr>';
                                $('.added_moment_row_position').append(addNewRowItemMoment);
                                iframeBody.html('');   
                                $("#link_moment").val('');
                                $("#add_moment").find("i").text('add_circle');
                                showSuccessMessage("Update successful");
                                $('html, body').animate({
                                    scrollTop: $("#active_on").offset().top  
                                }, 'slow'); 
                            }
                        }
                    }
                }
            });
        }else{
            alert("Remplissez au moins un champs pour ajouté");
        }
        });

        $(document).on('click', '.details_moment_link_icon_active', function(e) { 
            egid = $(this).attr('data-egid');
            egstatus = $(this).attr('data-status');
            updateStatusEgDetailsMoment(this, egid, egstatus);
        });

        $(document).on('click', '.moment-edit', function(e) { 
            e.preventDefault(); 
        
            var id_egblockbuilder_moment = $(this).attr('data-egid');
            var title_moment = $(this).attr('data-title');
            var link_moment = $(this).attr('data-link');
             
            $("#link_moment").val(link_moment);
            // Clear any previously added hidden input
            $('#egblockbuilder_items_form input[name="id_egblockbuilder_moment"]').remove();

            // Append hidden input with id_egblockbuilder_moment
            $('#egblockbuilder_items_form').append('<input type="hidden" id="id_egblockbuilder_moment" name="id_egblockbuilder_moment" value="'+id_egblockbuilder_moment+'">');
            
            // Fill the input fields with the existing values from the row
            var iframe = $('#title_moment_ifr');
            if (iframe.length) {
                // Access the body of the iframe
                var iframeBody = iframe.contents().find('body'); 
                // Get the HTML content inside the body
                iframeBody.html(title_moment);  
            }
                    // Scroll to the top of the iframe
            $('html, body').animate({
                scrollTop: $("#type").offset().top
            }, 'slow');
            $("#add_moment").find("i").text('save');
        });

        $(document).on('click', '.delete-moment', function(e) { 
            e.preventDefault(); 
            id_egblockbuilder_moment = $(this).attr('data-egid');
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=deleteEgItemDetailsMoment&ajax=true&id_egblockbuilder_moment=' + id_egblockbuilder_moment,
                headers: {"cache-control": "no-cache"},
                cache: false,
                dataType: 'json',
                data:{id_egblockbuilder_moment:id_egblockbuilder_moment},
                success:function(response) { 
                    if(response == 'success') {
                        selector = '.link-icon-delete[data-egid="' + id_egblockbuilder_moment + '"]';
                        $(selector).parents('tr').hide();
                        showSuccessMessage("Update successful");
                    }
                }
            });
        });

        // Drag And drop EG Moment
        $(".added_moment_row_position").sortable({
            cursor: "move",
            placeholder: "ui-sortable-placeholder",
            delay: 150,
            stop: function () {
                var selectedData = new Array();
                $('.added_moment_row_position>tr').each(function () {
                    selectedData.push($(this).attr("id"));
                });
                updateItemDetailsMomentOrder(selectedData);
            }
        });

        // ----------------------------END EG Moment ------------------------
        // Manage Status EG Item Moment
        function updateStatusEgDetailsMoment(elm, id_egblockbuilder_moment, status)
        {
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=updateStatusEgDetailsMoment&ajax=true&id_egblockbuilder_moment=' + id_egblockbuilder_moment + '&status=' + status,
                contentType: false,
                processData: false,
                success:function(response) {  
                    if(response == 1) {  
                        if (status == 0) {
                            $(elm).removeClass('eg-action-enabled');
                            $(elm).addClass('eg-action-disabled');
                            $(elm).find('i').text('clear');
                            $(elm).attr("data-status", 1);
                        } else {
                            $(elm).removeClass('eg-action-disabled');
                            $(elm).addClass('eg-action-enabled');
                            $(elm).find('i').text('check');
                            $(elm).attr("data-status", 0);
                        }
                        showSuccessMessage("Update successful");
                    } 
                }
            });
        }

        // --------------------- get all moments --------------------------
        function getAllMoments(id_egblockbuilder_items) {
            
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=allEgDetailsMoment&ajax=true',
                data: {id_egblockbuilder_items: id_egblockbuilder_items},
                dataType: 'json',
                success: function (response) {
                    console.log(response)
                    if (response.length > 0) {
                        $(".added_moment_row_position tbody").empty();
                        for (var i = 0; i < response.length; i++) {
                            var moment = response[i];
                            var imm = '<img data-id="'+moment.image_moment+'" width="100" heigth="100" src="'+src_rac+moment.image_moment+'" >';
                           
                            var classe = moment.active == 1 ? "eg-action-enabled" : "eg-action-disabled";
                            var content = moment.active == 1 ? "check" : "clear";
                            var status = moment.active == 1 ? 0 : 1;
                            var addNewRowItemMoment = '<tr id="' + moment.id_egblockbuilder_moment + '" data-egid="' + moment.id_egblockbuilder_moment + '" data-title="' + moment.title_moment + '" class="odd">\n' +
                                '<td class="pointer" onclick="">\n' +
                                ' <div class="position-drag-gallery">\n' +
                                '<i class="material-icons">drag_indicator</i>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '<td class="pointer" onclick="">' + moment.id_egblockbuilder_moment + '</td>\n' +
                                '<td class="pointer" onclick="">' + moment.title_moment + '</td>\n' + 
                                '<td class="pointer" onclick="">' + moment.link_moment + '</td>\n' + 
                                '<td class="pointer" onclick="">' + imm + '</td>\n' + 
                                '<td class="pointer" onclick="">\n' +
                                '<div id="">\n' +
                                '<a class="details_moment_link_icon_active btn" data-status="' + status + '" data-egid="' + moment.id_egblockbuilder_moment + '" data-title="' + moment.title_moment + '">\n' +
                                '<i class="material-icons ' + classe + '">' + content + '</i>\n' +
                                '</a>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '<td class="pointer" onclick="">\n' +
                                '<div class="btn-group-action">\n' +
                                '<div class="btn-group">\n' +
                                '<a href="" data-egid="' + moment.id_egblockbuilder_moment + '" data-title="' + moment.title_moment +  '" data-link="' + moment.link_moment +'" title="" class="btn tooltip-link moment-edit link-icon-delete">\n' +
                                '<i class="material-icons">edit</i>\n' +
                                '</a>\n' +
                                '</div>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '<td class="pointer" onclick="">\n' +
                                '<div class="btn-group-action">\n' +
                                '<div class="btn-group">\n' +
                                '<a href="" data-egid="' + moment.id_egblockbuilder_moment + '" data-title="' + moment.title_moment + '" title="" class="btn tooltip-link link-icon-delete delete-moment">\n' +
                                '<i class="material-icons">delete</i>\n' +
                                '</a>\n' +
                                '</div>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '</tr>';
                            $('.added_moment_row_position').append(addNewRowItemMoment);
                        }
                    }
                }
            });
        }

        // Update Item Details Moment Order
        function updateItemDetailsMomentOrder(data) {
 
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=updateItemDetailsMomentOrder&ajax=true',
                data: {ids: data},
                success: function (response) {
                    if (response == 'success') {
                        showSuccessMessage("Update successful");
                    }
                }
            });
        }
         /***************************  sacs   ***********************/ 
         // --------------------- get all sacs --------------------------
        var id_egblockbuilder_items = $("#id_egblockbuilder_items").val();
        getAllSacs(id_egblockbuilder_items);

        // ----------------------------Save EG Sac------------------------
        $('#add_sac').on('click', function (e) {
            e.preventDefault();

            var id_egblockbuilder_items = $("#id_egblockbuilder_items").val();
            var id_egblockbuilder_sac = $("#id_egblockbuilder_sac").val();
            
            var formData = new FormData();
            if (id_egblockbuilder_items) { 
                formData.append("id_egblockbuilder_items", id_egblockbuilder_items);
            }
            if (id_egblockbuilder_sac) { 
                formData.append("id_egblockbuilder_sac", id_egblockbuilder_sac);
            }
            
            var title_sac = $('.tree-selected').find('input').val();

            if (title_sac) { 
                formData.append("title_sac", title_sac);
            }  

            if ( title_sac  ) {
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=saveEgDetailsSac&ajax=true',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    for (let index = 0; index < response.length; index++) {
                        const EgDetailsSac = response[index];
                        if (EgDetailsSac) {
                            var classe, content, status, $active_text;

                            if (EgDetailsSac.active == 0) {
                                classe = "eg-action-disabled";
                                content = "clear";
                                status = 1;
                            } else {
                                classe = "eg-action-enabled";
                                content = "check";
                                status = 0;
                            }

                            $active_text = EgDetailsSac.active == 1 ? enable : disable;

                            if (id_egblockbuilder_sac) {
                                // Update existing row
                                var $row = $('tr[data-egid="' + id_egblockbuilder_sac + '"]');
                                $row.find('td:eq(1)').text(EgDetailsSac.id_egblockbuilder_sac);
                                $row.find('td:eq(2)').text(EgDetailsSac.title_sac); 
                                var upd = "updateEgItemDetailsSac("+EgDetailsSac.id_egblockbuilder_sac+"); return false;";
                                
                                //$row.find('td:eq(4)').find('.sac-edit').attr("onclick",upd); 
                                $row.find('.details_sac_link_icon_active')
                                    .attr('data-status', status)
                                    .data('status', status)
                                    .find('i')
                                    .removeClass('eg-action-enabled eg-action-disabled')
                                    .addClass(classe)
                                    .text(content);
                                $('html, body').animate({
                                    scrollTop: $("#active_on").offset().top  
                                }, 'slow'); 
                            } else {
                                // Add new row
                                var addNewRowItemSac = '<tr id="' + EgDetailsSac.id_egblockbuilder_sac + '" data-egid="' + EgDetailsSac.id_egblockbuilder_sac + '" data-title="' + EgDetailsSac.title_sac + '" class="odd">\n' +
                                    '<td class="pointer" onclick="">\n' +
                                    ' <div class="position-drag-gallery">\n' +
                                    '<i class="material-icons">drag_indicator</i>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '<td class="pointer" onclick="">' + EgDetailsSac.id_egblockbuilder_sac + '</td>\n' +
                                    '<td class="pointer" onclick="">' + EgDetailsSac.title_sac + '</td>\n' + 
                                    '<td class="pointer" onclick="">\n' +
                                    '<div id="">\n' +
                                    '<a class="details_sac_link_icon_active btn" data-status="' + status + '" data-egid="' + EgDetailsSac.id_egblockbuilder_sac + '" data-title="' + EgDetailsSac.title_sac + '">\n' +
                                    '<i class="material-icons ' + classe + '">' + content + '</i>\n' +
                                    '</a>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    //'<td class="pointer" onclick="">\n' +
                                    //'<div class="btn-group-action">\n' +
                                    //'<div class="btn-group">\n' +
                                    //'<a href="" data-egid="' + EgDetailsSac.id_egblockbuilder_sac + '" data-title="' + EgDetailsSac.title_sac + '" title="" class="btn tooltip-link sac-edit link-icon-delete">\n' +
                                    //'<i class="material-icons">edit</i>\n' +
                                    //'</a>\n' +
                                    //'</div>\n' +
                                    //'</div>\n' +
                                    '</td>\n' +
                                    '<td class="pointer" onclick="">\n' +
                                    '<div class="btn-group-action">\n' +
                                    '<div class="btn-group">\n' +
                                    '<a href="" data-egid="' + EgDetailsSac.id_egblockbuilder_sac + '" data-title="' + EgDetailsSac.title_sac + '" title="" class="btn tooltip-link link-icon-delete delete-sac">\n' +
                                    '<i class="material-icons">delete</i>\n' +
                                    '</a>\n' +
                                    '</div>\n' +
                                    '</div>\n' +
                                    '</td>\n' +
                                    '</tr>';
                                $('.added_sacs_row_position').append(addNewRowItemSac);
                                $('html, body').animate({
                                    scrollTop: $("#active_on").offset().top  
                                }, 'slow'); 
                            }
                        }
                    }
                }
            });
        }else {
            alert("Choisissez une catégorie");
        }
        });

        $(document).on('click', '.details_sac_link_icon_active', function(e) { 
            egid = $(this).attr('data-egid');
            egstatus = $(this).attr('data-status');
            updateStatusEgDetailsSac(this, egid, egstatus);
        });

        $(document).on('click', '.sac-edit', function(e) { 
            e.preventDefault(); 
        
            var id_egblockbuilder_sac = $(this).attr('data-egid');
            var title_sac = $(this).attr('data-title');
            
            // Clear any previously added hidden input
            $('#egblockbuilder_items_form input[name="id_egblockbuilder_sac"]').remove();

            // Append hidden input with id_egblockbuilder_sac
            $('#egblockbuilder_items_form').append('<input type="hidden" id="id_egblockbuilder_sac" name="id_egblockbuilder_sac" value="'+id_egblockbuilder_sac+'">');
            
            // Fill the input fields with the existing values from the row
            /*
            var title_sac = $('.tree-selected').find('input').val();
            if (title_sac) { 
                formData.append("title_sac", title_sac);
            } */
        });

        $(document).on('click', '.delete-sac', function(e) { 
            e.preventDefault(); 
            id_egblockbuilder_sac = $(this).attr('data-egid');
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=deleteEgItemDetailsSac&ajax=true&id_egblockbuilder_sac=' + id_egblockbuilder_sac,
                headers: {"cache-control": "no-cache"},
                cache: false,
                dataType: 'json',
                data:{id_egblockbuilder_sac:id_egblockbuilder_sac},
                success:function(response) { 
                    if(response == 'success') {
                        selector = '.link-icon-delete[data-egid="' + id_egblockbuilder_sac + '"]';
                        $(selector).parents('tr').hide();
                        showSuccessMessage("Update successful");
                    }
                }
            });
        });

        // Drag And drop EG Sac
        $(".added_sacs_row_position").sortable({
            cursor: "move",
            placeholder: "ui-sortable-placeholder",
            delay: 150,
            stop: function () {
                var selectedData = new Array();
                $('.added_sacs_row_position>tr').each(function () {
                    selectedData.push($(this).attr("id"));
                });
                updateItemDetailsSacOrder(selectedData);
            }
        });

        // ----------------------------END EG Sac ------------------------
        // Manage Status EG Item Sac
        function updateStatusEgDetailsSac(elm, id_egblockbuilder_sac, status)
        {
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=updateStatusEgDetailsSac&ajax=true&id_egblockbuilder_sac=' + id_egblockbuilder_sac + '&status=' + status,
                contentType: false,
                processData: false,
                success:function(response) {  
                    if(response == 1) {  
                        if (status == 0) {
                            $(elm).removeClass('eg-action-enabled');
                            $(elm).addClass('eg-action-disabled');
                            $(elm).find('i').text('clear');
                            $(elm).attr("data-status", 1);
                        } else {
                            $(elm).removeClass('eg-action-disabled');
                            $(elm).addClass('eg-action-enabled');
                            $(elm).find('i').text('check');
                            $(elm).attr("data-status", 0);
                        }
                        showSuccessMessage("Update successful");
                    } 
                }
            });
        }

        // --------------------- get all sacs --------------------------
        function getAllSacs(id_egblockbuilder_items) {
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=AllEgDetailsSac&ajax=true',
                data: {id_egblockbuilder_items: id_egblockbuilder_items},
                dataType: 'json',
                success: function (response) { 
                    if (response.length > 0) {
                        $(".added_sacs_row_position tbody").empty();
                        for (var i = 0; i < response.length; i++) {
                            var sac = response[i];
                            var classe = sac.active == 1 ? "eg-action-enabled" : "eg-action-disabled";
                            var content = sac.active == 1 ? "check" : "clear";
                            var status = sac.active == 1 ? 0 : 1;
                            var addNewRowItemSac = '<tr id="' + sac.id_egblockbuilder_sac + '" data-egid="' + sac.id_egblockbuilder_sac + '" data-title="' + sac.title_sac + '" class="odd">\n' +
                                '<td class="pointer" onclick="">\n' +
                                ' <div class="position-drag-gallery">\n' +
                                '<i class="material-icons">drag_indicator</i>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '<td class="pointer" onclick="">' + sac.id_egblockbuilder_sac + '</td>\n' +
                                '<td class="pointer" onclick="">' + sac.title_sac + '</td>\n' + 
                                '<td class="pointer" onclick="">\n' +
                                '<div id="">\n' +
                                '<a class="details_sac_link_icon_active btn" data-status="' + status + '" data-egid="' + sac.id_egblockbuilder_sac + '" data-title="' + sac.title_sac + '">\n' +
                                '<i class="material-icons ' + classe + '">' + content + '</i>\n' +
                                '</a>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                //'<td class="pointer" onclick="">\n' +
                                //'<div class="btn-group-action">\n' +
                                //'<div class="btn-group">\n' +
                                //'<a href="" data-egid="' + sac.id_egblockbuilder_sac + '" data-title="' + sac.title_sac + '" title="" class="btn tooltip-link sac-edit link-icon-delete">\n' +
                                //'<i class="material-icons">edit</i>\n' +
                                //'</a>\n' +
                                //'</div>\n' +
                                //'</div>\n' +
                                //'</td>\n' +
                                '<td class="pointer" onclick="">\n' +
                                '<div class="btn-group-action">\n' +
                                '<div class="btn-group">\n' +
                                '<a href="" data-egid="' + sac.id_egblockbuilder_sac + '" data-title="' + sac.title_sac + '" title="" class="btn tooltip-link  link-icon-delete delete-sac">\n' +
                                '<i class="material-icons">delete</i>\n' +
                                '</a>\n' +
                                '</div>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '</tr>';
                            $('.added_sacs_row_position').append(addNewRowItemSac);
                        }
                    }
                }
            });
        }

        // Update Item Details Sac Order
        function updateItemDetailsSacOrder(data) { 
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=updateItemDetailsSacOrder&ajax=true',
                data: {ids: data},
                success: function (response) {
                    if (response == 'success') {
                        showSuccessMessage("Update successful");
                    }
                }
            });
        }


            // Function to get query parameter by name
    function getParameterByName(name) {
        var url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    // Get the id_egblockbuilder from the URL
    var id_egblockbuilder = getParameterByName('id_egblockbuilder');
 
    // Check if id_egblockbuilder is found
    if (id_egblockbuilder) {
        // Iterate over each element with class 'delete' under '#table-egblockbuilder_items'
        $('#table-egblockbuilder_items .delete').each(function() {
            // Get the value of the 'onclick' attribute
            var onclickValue = $(this).attr('onclick');

            // Log the original onclick value to the console for verification
            console.log('Original Onclick Value:', onclickValue);

            // Use a regular expression to find and modify the URL in the onclick value
            var modifiedOnclickValue = onclickValue.replace(
                /(id_egblockbuilder_items=\d+)/,
                `$1&id_egblockbuilder=${id_egblockbuilder}`
            );

            // Set the modified onclick value back to the element
            $(this).attr('onclick', modifiedOnclickValue);

            // Log the modified onclick value to the console for verification
            console.log('Modified Onclick Value:', modifiedOnclickValue);
        });
    } else {
        console.error('id_egblockbuilder not found in URL');
    }
});
 
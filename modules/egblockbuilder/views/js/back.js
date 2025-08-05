$(document).ready(function() {
    
    // Toggle functionality for collapsible categories
    $('.toggle-children').on('click', function() {
        var $children = $(this).next('ul');
        $children.toggle();
        $(this).text($children.is(':hidden') ? '[+]' : '[-]');
    });

    $(".visualiser_link_rewrite").hide();
 

    
    var id_egblockbuilder_items = $("#id_egblockbuilder_items").val();
    var firstOption = $('#productIds option').eq(0);
    $("#add_product_arrive").attr('data-id', firstOption.val()).attr('data-title', firstOption.text());

    $(document).on('click', '.active-result', function() {
        var index = $(this).data('option-array-index');
        var selectedOption = $('#productIds option').eq(index);
        $("#add_product_arrive").attr('data-id', selectedOption.val()).attr('data-title', selectedOption.text());
    });

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
                            <a href="#" data-egid="${id}" class="btn tooltip-link product-edit link-icon-delete delete-arrive" data-toggle="pstooltip" title="Supprimer définitivement cette item.">
                                <i class="material-icons">delete</i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        `;
        $('.selected_products_row_position').append(newRow);
    }

    var chosenProducts = $('#chosen_products').val();
    if (chosenProducts) {
        chosenProducts.split(',').forEach(function(id) {
            var option = $('#productIds').find('option[value="' + id + '"]');
            if (option.length) {
                addNewRow(id, option.text());
            }
        });
    }

    $('#add_product_arrive').on('click', function() {
        var id = $(this).attr('data-id');
        var produit = $(this).attr('data-title');
        var chosenProductsArray = $('#chosen_products').val().split(',');

        if (!chosenProductsArray.includes(id)) {
            chosenProductsArray.push(id);
            addNewRow(id, produit);
            $('#chosen_products').val(chosenProductsArray.join(','));

            if ($('#id_egblockbuilder_items').length) {
                $.ajax({
                    type: 'POST',
                    url: EgBlockBuilderItemsController + '&action=saveEgDetailsArrive&ajax=true',
                    data: {
                        id_egblockbuilder_items: $('#id_egblockbuilder_items').val(),
                        chosen_products: $('#chosen_products').val()
                    },
                    dataType: 'json',
                    success: function() {
                        $('html, body').animate({ scrollTop: $("#active_on").offset().top }, 'slow');
                    }
                });
            }
        } else {
            alert('Produit déjà exist dans votre liste');
        }
    });

    $(document).on('click', '.delete-arrive', function() {
        var deleteId = $(this).data('egid');
        $(this).closest('tr').remove();

        var updatedProducts = $('#chosen_products').val().split(',').filter(product => product !== deleteId.toString());
        $('#chosen_products').val(updatedProducts.join(','));

        if ($('#id_egblockbuilder_items').length) {
            $.ajax({
                type: 'POST',
                url: EgBlockBuilderItemsController + '&action=saveEgDetailsArrive&ajax=true',
                data: {
                    id_egblockbuilder_items: $('#id_egblockbuilder_items').val(),
                    chosen_products: $('#chosen_products').val()
                },
                dataType: 'json'
            });
        }
    });

    $(".selected_products_row_position").sortable({
        cursor: "move",
        placeholder: "ui-sortable-placeholder",
        delay: 150,
        stop: function() {
            var selectedData = $(".selected_products_row_position>tr").map(function() {
                return $(this).attr("id");
            }).get();
            updateItemDetailsMarqueOrder(selectedData);
        }
    });

    if ($("#id_egblockbuilder_items").length) {
        $("#type").attr('disabled', true);
    }

    $("#table-egblockbuilder_items tbody").find('tr').each(function() {
        var typeValue = $(this).find('.column-type').text().trim();
        
        // Hide all title columns initially
        $(this).find('[class*="column-title_"]').hide();
        $('[name*="egblockbuilder_itemsFilter_a!title"]').hide();
        // Show only the corresponding title column based on type
        if (typeValue >= 1 && typeValue <= 5) {
            $(this).find('.column-title_' + typeValue).show();
        }
    
        // Handle both header and filter rows
        var index = parseInt(typeValue);
        if (index >= 1 && index <= 5) {
            // Handle headers and filters
            $('#table-egblockbuilder_items thead tr').each(function() {
                var $row = $(this);
                // Skip if it's not a header or filter row
                if (!$row.hasClass('nodrag') && !$row.hasClass('filter')) {
                    return;
                }
                
                $row.find('th').each(function(i) {
                    // Skip first column (checkbox), last column (actions), and non-title columns
                    if (i === 0 || i === 1 || i >= 7) {
                        $(this).show();
                        return;
                    }
                    
                    // For title columns (index 2-6)
                    if (i === index + 1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        }
    });

    function toggleFields() {
        var selectedValue = $('#type').val();
        var fieldsToHide = [
            '.line_products', '#productIds', '#selected_products', '#nb_produit', '#image_produit',
            '#banner', '#banner_mobile', '#link_button', '#text_button', '[name="background_color"]', '#first_image_4',
            '.add_double','.added_double','.line_double','.html_double','#double_image', '#double_image_mobile', '#type_video_double','#video_double',
            , '#link_button_3', '#text_button_3','#type_video_banniere', '#video_banniere',
            '#link_button_4', '#text_button_4', '#link_button_5', '#text_button_5'
        ];

        fieldsToHide.forEach(function(selector) {
            $(document).find(selector).each(function() {
                if ($(this).is('[id*="image"], [id*="banner"]')) {
                    $(this).parent().parent().parent().parent().hide();
                } else {
                    $(this).closest('.form-group').hide();
                }
            });
        });
        $(".added_double_row_position_4").hide();
        $(".added_double_row_position_5").hide();
        $(".add_4").hide();
        $(".add_5").hide(); 
        $(".double_designation").hide();
        switch (selectedValue) {
            case '1':
                $('#productIds, #selected_products, #nb_produit, #image_produit').each(function() {
                    if ($(this).is('[id*="image"], [id*="banner"]')) {
                        $(this).parent().parent().parent().parent().show();
                    } else {
                        $(this).closest('.form-group').show();
                    }
                });
                $(".line_products").closest('.form-group').show();
                break;
            case '2':
                $('#banner, #banner_mobile,#type_video_banniere, #link_button, #text_button').each(function() {
                    if ($(this).is('[id*="image"], [id*="banner"]')) {
                        $(this).parent().parent().parent().parent().show();
                    } else {
                        $(this).closest('.form-group').show();
                    }
                });
                break;
            case '3':
                $('[name="background_color"]').closest('.form-group').show(); 
                $("#link_button_3").parent().parent().show();
                $("#text_button_3").parent().parent().show();
                break;
            case '4':
                $('.add_double,.line_double, #double_image, #double_image_mobile, #type_video_double').each(function() {
                    if ($(this).is('[id*="image"], [id*="banner"]')) {
                        $(this).parent().parent().parent().parent().show();
                    } else {
                        $(this).closest('.form-group').show();
                    }
                });
                $(".added_double_row_position_4").show();
                $(".add_4").show();
                break;
            case '5':
                $('.add_double,.line_double,textarea[id^="text_double_"], #double_image, #double_image_mobile, #type_video_double').each(function() {
                    if ($(this).is('[id*="image"], [id*="banner"]')) {
                        $(this).parent().parent().parent().parent().show();
                    } else {
                        $(this).closest('.form-group').show();
                    }
                }); 
                $(".added_double_row_position_5").show();
                $(".add_5").show();
                $(".double_designation").show();
                break;
        }
    }

    function toggleInputs() {
        var selectedValue = $('#type').val();
        $('textarea[id^="title_"], textarea[id^="text_"]').each(function() {
            if (!$(this).attr('id').includes('button')) {
                $(this).closest('.form-group').hide();
            }
        });
        $('#title_' + selectedValue + '_1, #text_' + selectedValue + '_1').each(function() {
            if (!$(this).attr('id').includes('button')) {
                $(this).closest('.form-group').show();
            }
        });
    }
    toggleInputs();
    toggleFields();
  
 
    $(document).on('change', '#type', function() {
        toggleInputs();
        toggleFields();
    }).change();
    $(document).on('change', '#type', function() {
        toggleInputs();
        toggleFields();
    }).change();
    $(document).on('change', '#type_video_banniere,#type_video_double', function() {
        var selectedValue = $(this).val();
        if (selectedValue !="video_type_image") {
            $(this).closest('.form-group').next().show();
        } else {
            $(this).closest('.form-group').next().hide();
        }
    }).change();
    
    function getParameterByName(name) {
        var url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    var id_egblockbuilder = getParameterByName('id_egblockbuilder');

    if (id_egblockbuilder) {
        $('#table-egblockbuilder_items .delete').each(function() {
            var onclickValue = $(this).attr('onclick');
            var modifiedOnclickValue = onclickValue.replace(/(id_egblockbuilder_items=\d+)/, `$1&id_egblockbuilder=${id_egblockbuilder}`);
            $(this).attr('onclick', modifiedOnclickValue);
        });
    } else {
        console.error('id_egblockbuilder not found in URL');
    }

    $(document).on('click', '.add_double', function(e) {
        e.preventDefault();
    
        var id_egblockbuilder_items = $("#id_egblockbuilder_items").val();
        var type = $(this).data('type');
        var double_image = $("#double_image")[0].files[0];
        var double_image_mobile = $("#double_image_mobile")[0].files[0];
        var type_video_double = $("#type_video_double").val();
        var video_double = $("#video_double").val();
        var text_double = $("#text_double_1_ifr").contents().find("body").html();
    
        var formData = new FormData();
        formData.append("id_egblockbuilder_items", id_egblockbuilder_items);
        formData.append("type", type);
        if (double_image) {
            formData.append("double_image", double_image);
        }
        if (double_image_mobile) {
            formData.append("double_image_mobile", double_image_mobile);
        }
        formData.append("type_video_double", type_video_double);
        formData.append("video_double", video_double);
        formData.append("text_double", text_double);
    
        $.ajax({
            type: 'POST',
            url: EgBlockBuilderItemsController + '&action=saveMultipleImages&ajax=true',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Handle success (e.g., update the UI, show a success message)
                    var classe = response.data.active == 0 ? "eg-action-disabled" : "eg-action-enabled";
                    var content = response.data.active == 0 ? "clear" : "check";
                    var videoTypeName = '';
                    switch (response.data.video) {
                        case 'video_type_image':
                            videoTypeName = 'Image';
                            break;
                        case 'video_type_youtube':
                            videoTypeName = 'Youtube';
                            break;
                        case 'video_type_vimeo':
                            videoTypeName = 'Vimeo';
                            break;
                        case 'video_type_other':
                            videoTypeName = 'Source';
                            break;
                    }
                    var video = `
                    <td class="center pointer">${videoTypeName}</td>
                    <td class="center pointer">${response.data.video_double}</td>
                `   ; 
                    var img = `
                        <td class="center pointer"><img width ="50px" height ="50px" src="/modules/egblockbuilder/views/img/${response.data.double_image}" alt="Double Image" /></td>
                        <td class="center pointer"><img width ="50px" height ="50px" src="/modules/egblockbuilder/views/img/${response.data.double_image_mobile}" alt="Double Image Mobile" /></td>
                    `;
                    var txt = ``;
                    if (response.data.type == '5') {
                            var txt = `<td class="center pointer">${response.data.text_double}</td>`;
                    }
                    var newRow = `
                        <tr>
                            <td class="center pointer">${response.data.id}</td>
                            ${img} 
                            ${video}  
                            ${txt}
                            <td class="center pointer">
                                <a class="multiple_images_active btn" data-status="${response.data.active}" data-egid="${response.data.id}" data-title="${response.data.text_double}">
                                    <i class="material-icons ${classe}">${content}</i>
                                </a>
                            </td>
                            <td class="center pointer">
                                <div class="btn-group-action">
                                    <div class="btn-group">
                                        <a href="#" data-egid="${response.data.id}" class="btn tooltip-link link-icon-delete delete-image-multiples" data-toggle="pstooltip" title="Supprimer définitivement cette item.">
                                            <i class="material-icons">delete</i>
                                        </a>
                                        <a href="#" data-egid="${response.data.id}" class="btn tooltip-link link-icon-edit edit-image-multiples" data-toggle="pstooltip" title="Modifier cette item.">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    $('.added_double_row_position_' + response.data.type).append(newRow);
                } else {
                    // Handle error (e.g., show an error message)
                    alert('Failed to save images');
                }
            }
        });
    });

    $(document).on('click', '.delete-image-multiples', function(e) {
        e.preventDefault();
        var id = $(this).data('egid');
        $.ajax({
            type: 'POST',
            url: EgBlockBuilderItemsController + '&action=deleteMultipleImages&ajax=true',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Remove the row from the table
                    $('tr').has('a[data-egid="' + id + '"]').remove();
                } else {
                    alert('Failed to delete image');
                }
            }
        });
    });

    $(document).on('click', '.edit-image-multiples', function(e) {
        e.preventDefault();
        var id = $(this).data('egid');
        // Fetch the data for the selected image and populate the form fields
        $.ajax({
            type: 'POST',
            url: EgBlockBuilderItemsController + '&action=getMultipleImage&ajax=true',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var data = response.data; 
                    $("#type").val(data.type);
                    $("#type_video_double").val(data.video);
                    $("#video_double").val(data.video_double);  
                    $("#text_double_1_ifr").contents().find("body").html(data.text_double);
                    $(".bloc_img").parent().parent().parent().remove();
                    $(".add_double").addClass("update_image");
                    $(".update_image").removeClass("add_double");
                    $(".update_image i").text("save");
                    $(".update_image").data("id", id);
                    // Handle image fields if necessary
                    if (data.double_image) { 
                        $("#double_image-name").parent().parent().parent().parent().append(`
                            <div class="form-group">
                                <div class="col-lg-12" id="banner-images-thumbnails">
                                    <div>
                                        <br><img class="bloc_img" width="200" alt="" src="/modules/egblockbuilder/views/img/`+data.double_image+`"><br>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                    if (data.double_image_mobile) { 
                        $("#double_image_mobile-name").parent().parent().parent().parent().append(`
                            <div class="form-group">
                                <div class="col-lg-12" id="banner-images-thumbnails">
                                    <div>
                                        <br><img class="bloc_img" width="200" alt="" src="/modules/egblockbuilder/views/img/`+data.double_image_mobile+`"><br>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                } else {
                    alert('Failed to retrieve image data');
                }
            }
        });
    });

    $(document).on('click', '.update_image', function(e) {
        e.preventDefault();

        var id = $(this).data('id');
        var id_egblockbuilder_items = $("#id_egblockbuilder_items").val();
        var type = $("#type").val();
        var double_image = $("#double_image")[0].files[0];
        var double_image_mobile = $("#double_image_mobile")[0].files[0];
        var type_video_double = $("#type_video_double").val();
        var video_double = $("#video_double").val();
        var text_double = $("#text_double_1_ifr").contents().find("body").html();

        var formData = new FormData();
        formData.append("id", id);
        formData.append("id_egblockbuilder_items", id_egblockbuilder_items);
        formData.append("type", type);
        if (double_image) {
            formData.append("double_image", double_image);
        }
        if (double_image_mobile) {
            formData.append("double_image_mobile", double_image_mobile);
        }
        formData.append("type_video_double", type_video_double);
        formData.append("video_double", video_double);
        formData.append("text_double", text_double);

        $.ajax({
            type: 'POST',
            url: EgBlockBuilderItemsController + '&action=updateMultipleImages&ajax=true',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Handle success (e.g., update the UI, show a success message)
                    location.reload();
                } else {
                    // Handle error (e.g., show an error message)
                    alert('Failed to update images');
                }
            }
        });
    });

    function getAllMultipleImages(id_egblockbuilder_items) {
        $.ajax({
            type: 'POST',
            url: EgBlockBuilderItemsController + '&action=getAllMultipleImages&ajax=true',
            data: {
                id_egblockbuilder_items: id_egblockbuilder_items
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    console.log(response.data);
                    response.data.forEach(function(item) {
                        console.log(item);
                        console.log(item.id);
                        console.log(item['id']);
                        var classe = item.active == 0 ? "eg-action-disabled" : "eg-action-enabled";
                        var content = item.active == 0 ? "clear" : "check";
                        var videoTypeName = '';
                        switch (item.video) {
                            case 'video_type_image':
                                videoTypeName = 'Image';
                                break;
                            case 'video_type_youtube':
                                videoTypeName = 'Youtube';
                                break;
                            case 'video_type_vimeo':
                                videoTypeName = 'Vimeo';
                                break;
                            case 'video_type_other':
                                videoTypeName = 'Source';
                                break;
                        }
                        var video = `
                        <td class="center pointer">${videoTypeName}</td>
                        <td class="center pointer">${item.video_double}</td>
                    `   ; 
                        var img = `
                            <td class="center pointer"><img width ="50px" height ="50px" src="/modules/egblockbuilder/views/img/${item.double_image}" alt="Double Image" /></td>
                            <td class="center pointer"><img width ="50px" height ="50px" src="/modules/egblockbuilder/views/img/${item.double_image_mobile}" alt="Double Image Mobile" /></td>
                        `;
                        var txt = ``;
                        if (item.type == '5') {
                             var txt = `<td class="center pointer">${item.text_double}</td>`;
                        }
                        var newRow = `
                            <tr>
                                <td class="center pointer">${item.id}</td>
                                ${img} 
                                ${video}  
                                ${txt}
                                <td class="center pointer">
                                    <a class="multiple_images_active btn" data-status="${item.active}" data-egid="${item.id}" data-title="${item.text_double}">
                                        <i class="material-icons ${classe}">${content}</i>
                                    </a>
                                </td>
                                <td class="center pointer">
                                    <div class="btn-group-action">
                                        <div class="btn-group">
                                            <a href="#" data-egid="${item.id}" class="btn tooltip-link link-icon-delete delete-image-multiples" data-toggle="pstooltip" title="Supprimer définitivement cette item.">
                                                <i class="material-icons">delete</i>
                                            </a>
                                            <a href="#" data-egid="${item.id}" class="btn tooltip-link link-icon-edit edit-image-multiples" data-toggle="pstooltip" title="Modifier cette item.">
                                                <i class="material-icons">edit</i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                        $('.added_double_row_position_' + item.type).append(newRow);
                    });
                } else {
                    alert('Failed to retrieve images');
                }
            }
        });
    }

    var id_egblockbuilder_items = $("#id_egblockbuilder_items").val();
    if (id_egblockbuilder_items) {
        getAllMultipleImages(id_egblockbuilder_items);
    }

    $(document).on('click', '.multiple_images_active', function(e) {
        e.preventDefault();
        var id = $(this).data('egid');
        var status = $(this).data('status');
        var newStatus = status == '1' ? '0' : '1';
        var $icon = $(this).find('i');

        $.ajax({
            type: 'POST',
            url: EgBlockBuilderItemsController + '&action=toggleImageStatus&ajax=true',
            data: { id: id, status: newStatus },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $icon.toggleClass('eg-action-enabled eg-action-disabled');
                    $icon.text(newStatus == '1' ? 'check' : 'clear');
                    $(this).data('status', newStatus);
                } else {
                    alert('Failed to update image status');
                }
            }.bind(this)
        });
    });
      $('[name="egblockbuilder_itemsFilter_type"]').parent().show();
      $('[name*="egblockbuilderFilter_link_rewrite"]').parent().hide();
      $('[name*="egblockbuilderFilter_a!id_egblockbuilder"]').hide();
});
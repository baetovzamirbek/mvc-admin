<script>
  var desc_id;

  $(function() {
    //DELETE ALL RECORD FROM DATABASE
    $(document).on('click', '#delete', function() {
      var id = $(this).data('id');
      $.ajax({
        type: 'POST',
        url: 'Jquery/delete',
        data: {
          id: id,
        },
        dataType: 'json',
        success: function(response) {
          if (response.message == 'ok') {
            $('#qty_' + id).remove();
            if (response.empty == 0) {
              $('table').append("<tbody id='empty_td'><tr><td colspan='6' align='center'>A table is empty</td></tr></tbody>");
              clearForm();
            }
            $('#toggleCard').css('display', 'none');
            $('#changeButton').html('Add a Product');
            clearForm();
          }
        }
      });
    });

    //ADD product TO DATABASE
    $(document).on('click', '#add', function() {
      var name = $('#inputName').val();
      if (name.length != 0) {
        var arr = [];
        $('textarea').each(function() {
          arr.push(this.value);
        });
        var price = $('#inputPrice').val();
        var category_id = $("#form-control option:selected").val();
        var category_name = $("#form-control option:selected").text();
        $.ajax({
          type: 'POST',
          url: 'Jquery/add',
          data: {
            name: name,
            price: price,
            description: arr,
            category_id: category_id,
            category_name: category_name,
          },
          dataType: 'json',
          success: function(response) {
            if (response.message == 'ok') {
              clearForm();
            }
            $('table').append(response.code);
            $('#empty_td').remove();
          }
        });
      } else {
        alert("Fill Name field!");
      }
    });

    /*when clicked #edit button GET information from DATABASE*/
    $(document).on('click', '#edit', function(e) {
      $("html, body").animate({
        scrollTop: 0
      }, "fast");
      var id = $(this).data('id');

      if ($('#toggleCard').is(':hidden') && $('#changeButton').text() === 'Add a Product' ||
        $('#toggleCard').is(':visible') && $('#changeButton').text() === 'Close') {
        $('#toggleCard').css('display', 'block');
        $('#textProd').html('Edit a product');
        $('#changeButton').html('Close');
        $('#add').css('display', 'none');
        $('#save').css('display', 'block');
      }
      $.ajax({
        type: 'POST',
        url: 'Jquery/get',
        data: {
          id: id,
        },
        dataType: 'json',
        success: function(response) {
          $('#form-control').val(response.category_id).change();
          $('#inputName').val(response.name);
          $('#inputPrice').val(response.price);
          $('#inputDescription1').val(response.description[0]);
          desc_id = response.desc_id;
          response.description.shift();
          $('#plus').hide();
          $('#clear').hide();
          response.description.forEach(element => {
            $('.text').append('<textarea class="form-control mb-3" id="inputDescription" rows="1">' + element + '</textarea>');
          });
          $('#save').data('id', response.id);
        }
      });
    });

    /*when clicked #save button-> UPDATE information in DATABASE*/
    $(document).on('click', '#save', function(e) {
      var arr = [];
      $('textarea').each(function() {
        arr.push(this.value);
      });
      var id = $('#save').data('id');
      var name = $('#inputName').val();
      var price = $('#inputPrice').val();
      var category_id = $("#form-control option:selected").val();
      var category_name = $("#form-control option:selected").text();
      $.ajax({
        type: 'POST',
        url: 'Jquery/update',
        data: {
          id: id,
          name: name,
          price: price,
          description: arr,
          category_id: category_id,
          category_name: category_name,
          desc_id: desc_id,
        },
        dataType: 'json',
        success: function(response) {
          if (response.message == 'ok') {
            clearForm();
            $("#qty_" + id).html(response.code);
            $('#toggleCard').hide();
            $('#changeButton').html('Add a Product');
          }
        }
      });
    });

    /*when clicked "Add new description field" button*/
    $(document).on('click', '#plus', function(e) {
      $('.text').append('<textarea class="form-control mb-3" id="inputDescription" rows="1"></textarea>');
    });
    /*when clicked "remove"field button*/
    $(document).on('click', '#clear', function(e) {
      $('#inputDescription').remove();
      $('#inputDescription').append('<textarea class="form-control mb-3" id="inputDescription" rows="1"></textarea>');
    });
  });

  //_________________________________________
  // -----------Functions--------------------
  //_________________________________________

  //Toggle button Hide and Show
  function toggle() {
    $('#plus').css('display', 'block');
    $('#clear').css('display', 'block');
    if ($('#toggleCard').is(':hidden')) {
      $('#toggleCard').css('display', 'block');
      $('#textProd').html('Add a new Product');
      $('#changeButton').html('Close');
      $('#add').css('display', 'block');
      $('#save').css('display', 'none');

    } else {
      $('#toggleCard').css('display', 'none');
      $('#changeButton').html('Add a Product');
      $('#add').css('display', 'none');
    }
    clearForm();
  }

  function clearForm() {
    $('#form-control').val('1').change();
    $('#inputName').val('');
    $('#inputPrice').val('');
    $('#inputDescription1').val('');
    $("textarea[id=inputDescription]").remove();
  }
</script>
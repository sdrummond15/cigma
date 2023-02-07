jQuery(document).ready(function ($) {
  $("#clients, #tasks").select2();
  $("#date_in").mask("99/99/9999");
  $("#date_out").mask("99/99/9999");

  $("#date_in").focusout(function () {
    if ($(this).val()) {
      if (validarData($(this).val()) == false) {
        alert("Data de ida inválida!");
        $(this).val("");
        $(this).focus();
        return false;
      }
    }
  });

  $("#date_out").focusout(function () {
    if ($(this).val()) {
      if (!$("#date_in").val()) {
        alert("Informe a data de ida!");
        $(this).val("");
        $("#date_in").focus();
        return false;
      }
      if (!validarDatas($("#date_in").val(), $("#date_out").val())) {
        alert(
          "A data final da entrega deve ser maior ou igual a data de início!"
        );
        $(this).val("");
        $(this).focus();
        return false;
      }
      if (validarData($(this).val()) == false) {
        alert("Data de ida inválida!");
        $(this).val("");
        $(this).focus();
        return false;
      }
    }
  });

  $("#report-form").submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
      type: "POST",
      url:
        getBaseURL() +
        "components/com_managements/controllers/tax_deliveries.php",
      data: formData,
      dataType: "json",
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        if(data){
          $("#results-reports").html(data.form);

          $("#results-reports .btn-pdf").click(function () {
            var timestamp = new Date().getTime();
            var img = new Image();
            img.src = data.img;
            var doc = new jsPDF("l");
            doc.addImage(img, 'png', 105, 10, 73, 15);
            doc.autoTable({
              startY: 35,
              headStyles: { fillColor: [47, 76, 18] },
              html: "#list-deliveries"
            });
            doc.setProperties({
              title: "Relatório Cigma"
            });
            doc.save('relatorio_entregas_'+timestamp+'.pdf');
          });
        }else{
          $("#results-reports").html('<br><h3 class="alert">Nenhum resultado encontrado!</h3>');
        }
        
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert("Some problem occurred, please try again.");
      }
    });
  });
});

function getBaseURL() {
  var url = location.href; // entire url including querystring - also: window.location.href;
  var baseURL = url.substring(0, url.indexOf("/", 14));

  if (baseURL.indexOf("http://localhost") != -1) {
    // Base Url for localhost
    var url = location.href; // window.location.href;
    var pathname = location.pathname; // window.location.pathname;
    var index1 = url.indexOf(pathname);
    var index2 = url.indexOf("/", index1 + 1);
    var baseLocalUrl = url.substr(0, index2);

    return baseLocalUrl + "/";
  } else {
    // Root Url for domain name
    return baseURL + "/";
  }
}

function validarData(dateString) {
  // First check for the pattern
  if (!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString)) return false;

  // Parse the date parts to integers
  var parts = dateString.split("/");
  var day = parseInt(parts[0], 10);
  var month = parseInt(parts[1], 10);
  var year = parseInt(parts[2], 10);

  // Check the ranges of month and year
  if (year < 1000 || year > 3000 || month == 0 || month > 12) {
    return false;
  }

  var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

  // Adjust for leap years
  if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
    monthLength[1] = 29;

  // Check the range of the day
  return day > 0 && day <= monthLength[month - 1];
}

function validarDatas(datein, dateout) {
  var indate = datein.split("/");
  var dayIn = parseInt(indate[0], 10);
  var monthIn = parseInt(indate[1], 10);
  var yearIn = parseInt(indate[2], 10);
  var outdate = dateout.split("/");
  var dayOut = parseInt(outdate[0], 10);
  var monthOut = parseInt(outdate[1], 10);
  var yearOut = parseInt(outdate[2], 10);
  if (yearOut + monthOut + dayOut < yearIn + monthIn + dayIn) {
    return false;
  }
  return true;
}

<html>
<head>
   <title>jsPDF Convert PDF to Base64 String</title>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
</head>
    <body>
        {{-- dd --}}
    </body>
</html>


<script>
    $(document).ready(function(){

        alert("dd")
        // $('#exportForm').click(function () {
            var pdf = new jsPDF('a', 'mm', 'a4');
            var firstPage = "<h1>hello</h1>";
            // var secondPage;
            console.log(firstPage)

            html2canvas("<h1>hello</h1>", {
                onrendered: function (canvas) {
                    firstPage = canvas.toDataURL('image/jpeg', 1.0);
                }
            });

            // html2canvas($('#second-page'), {
            //     onrendered: function (canvas) {
            //         secondPage = canvas.toDataURL('image/jpeg', 1.0);
            //     }
            // });


            setTimeout(function () {
                pdf.addImage(firstPage, 'jpeg', 5, 5, 200, 0);
                // pdf.addPage();
                // pdf.addImage(secondPage, 'JPEG', 5, 5, 200, 0);
                pdf.save("export.pdf");
            }, 150);
    })
    // });
</script>

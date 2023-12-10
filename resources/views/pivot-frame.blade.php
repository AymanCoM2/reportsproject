<!DOCTYPE html>
<html lang="en">
{{-- https://jou.mine.nu:8505 --}}
<meta name="csrf-token" content="{{ csrf_token() }}" />

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/pivot-styling-1.css') }}" />
    <title>Home Side Bar</title>
</head>

<body>
    @livewire('Navreport', ['theQueryId' => $theQueryId])
    <script src="{{ asset('landing/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('js/pivot-js-1.js') }}"></script>
    <script>
        var links = document.querySelectorAll('a');
        links.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                var hrefValue = link.getAttribute('href');
                document.getElementById('inlineFrameExample').src = hrefValue;
                var parentLi = link.closest('li');
                var allLiElements = document.querySelectorAll('li');
                allLiElements.forEach(function(li) {
                    li.style.backgroundColor = ''; // Remove background color
                });
                if (parentLi) {
                    parentLi.style.backgroundColor = 'yellow';
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const intervalId = setInterval(afterDocumentIsReady, 3000);
        }); // End OF Document Ready

        function reloadThePage() {
            location.reload();
        }

        function afterDocumentIsReady() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }); // Setting Up the Ajax # 1 
            $.ajax({
                type: 'POST',
                url: "{{ route('get-pivot-count') }}",
                data: {
                    qId: $('#queryId').val(),
                    usrId: $("#currentUserId").val(),
                },
                success: function(data) {
                    console.log("We Got the Data : ");
                    let mainCount = $('#realCount').val();
                    console.log(data.count);
                    console.log(mainCount);
                    console.log($('#queryId').val());
                    console.log($("#currentUserId").val());
                    if (data.count != mainCount) {
                        reloadThePage();
                    }
                },
                error: function(ee) {
                    console.log("Err");
                    console.log(ee);
                }, // End of Error Option 
            }) // End Of Ajax call 
        } // End Of afterDocumentIsReady Function;  
    </script>

</body>

</html>

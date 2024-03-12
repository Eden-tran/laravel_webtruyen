<script src="{{ asset('FE') }}/js/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
{{-- <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script> --}}
<script>
    $(".nav-item").hover(
        function(e) {
            $(this).addClass("show");
            $(this).children(1).addClass("show");
        },
        function() {
            var that = $(this);
            setTimeout(function() {
                if (!that.is(":hover")) {
                    that.removeClass("show");
                    that.children(1).removeClass("show");
                }
            }, 100); // 1 second
        }
    );
</script>

<script src="{{ asset('FE') }}/js/bootstrap.min.js"></script>
<script src="{{ asset('FE') }}/js/main.js"></script>

(function($) {
    function readURL(input) {
        if (validateFile(input)) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#' + input.id + '-preview').attr('src', e.target.result)
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function validateFile(input) {
        return input.files && input.files[0];
    }
        
    $(document).ready(function() {
        $('input[type="file"].image-input').on('change', function() {
            readURL(this);
        });
    });
})(jQuery);
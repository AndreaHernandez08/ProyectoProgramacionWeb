// Central JS: admin AJAX handler + client-side validations
(function($){
    $(function(){
        // Helper: mostrar error en un formulario
        function showError($form, msg, timeout){
            timeout = typeof timeout === 'number' ? timeout : 3500;
            var $err = $('<div class="client-error" style="background:#fee;border:1px solid #f5c6cb;color:#8a1f1f;padding:8px;margin-bottom:8px;border-radius:4px">'+msg+'</div>');
            $form.prepend($err);
            setTimeout(function(){ $err.fadeOut(250,function(){ $err.remove(); }); }, timeout);
        }

        // Admin: actualizar stock vía AJAX
        $('form[action="admin_update_stock.php"]').on('submit', function(e){
            e.preventDefault();
            var $form = $(this);
            var url = $form.attr('action');
            var data = $form.serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res){
                if (res && res.success) {
                    var $stock = $form.closest('.pro_informacion').find('.stock-count');
                    if (!$stock.length) $stock = $form.closest('.Producto').find('.stock-count');
                    if ($stock.length && typeof res.new_total !== 'undefined' && res.new_total !== null) {
                        $stock.text(res.new_total);
                    }
                    var $msg = $('<div class="client-error" style="background:#e9f7e9;border:1px solid #b6e6b6;color:#176b19;padding:6px;margin-top:6px;border-radius:4px">'+(res.message||'Existencia actualizada.')+'</div>');
                    $form.prepend($msg);
                    setTimeout(function(){ $msg.fadeOut(300,function(){ $msg.remove(); }); }, 2500);
                } else {
                    var text = (res && res.message) ? res.message : 'Error al actualizar.';
                    showError($form, text, 3500);
                }
            }).fail(function(xhr){
                var txt = 'Error de red al actualizar.';
                try { var j = JSON.parse(xhr.responseText); if (j && j.message) txt = j.message; } catch(e){}
                showError($form, txt, 3500);
            });
        });

        // UTIL: buscar un formulario que contenga campos concretos
        function findFormWithFields(fieldNames){
            var selector = fieldNames.map(function(n){ return 'input[name="'+n+'"]'; }).join(',');
            var $field = $(selector).first();
            if ($field.length) return $field.closest('form');
            return $();
        }

        // Checkout validation: buscar formulario que tenga campos de envío
        var $chk = findFormWithFields(['ship_name','ship_address1','ship_city','ship_zip','ship_country']);
        if ($chk.length) {
            $chk.on('submit', function(e){
                var name = $.trim($chk.find('input[name="ship_name"]').val());
                var addr = $.trim($chk.find('input[name="ship_address1"]').val());
                var city = $.trim($chk.find('input[name="ship_city"]').val());
                var zip = $.trim($chk.find('input[name="ship_zip"]').val());
                var country = $.trim($chk.find('input[name="ship_country"]').val());
                var zipRe = /^\d{4,10}$/; // numeric zip check
                var countryRe = /^[A-Za-z]{2,3}$/;
                if (!name || !addr || !city) { showError($chk, 'Completa nombre, dirección y ciudad.'); e.preventDefault(); return false; }
                if (!zipRe.test(zip)) { showError($chk, 'Código postal inválido (solo números, 4-10 dígitos).'); e.preventDefault(); return false; }
                if (!countryRe.test(country)) { showError($chk, 'Código de país inválido (p.ej. US, MX).'); e.preventDefault(); return false; }
                return true;
            });
        }

        // Validación de stock en tiempo real (AJAX) para inputs de cantidad en el carrito
        // Guardar valor previo al foco
        $(document).on('focus', 'input[name^="qty["', function(){
            var $t = $(this);
            $t.data('prev', $t.val());
        });

        // Función que realiza la comprobación AJAX para un input dado
        function checkStockForInput($inp, val) {
            var rawName = $inp.attr('name');
            var m = rawName && rawName.match(/^qty\[(.+)\]$/);
            if (!m) return;
            var id = m[1];
            if (val <= 0) return;

            $.ajax({
                url: 'check_stock.php',
                method: 'POST',
                data: { id: id, qty: val },
                dataType: 'json'
            }).done(function(res){
                if (!res || !res.ok) {
                    var err = (res && res.error) ? res.error : 'Error al comprobar stock.';
                    showError($inp.closest('form'), err);
                    $inp.val($inp.data('prev') || 1);
                    return;
                }
                if (typeof res.available !== 'undefined' && res.available < val) {
                    var msg = 'Solo ' + res.available + ' unidad(es) disponibles' + (res.name ? ' para ' + res.name : '') + '.';
                    showError($inp.closest('form'), msg);
                    $inp.val(res.available);
                } else {
                    var $ok = $('<div class="client-ok" style="background:#e9f7e9;border:1px solid #b6e6b6;color:#176b19;padding:6px;margin-top:6px;border-radius:4px">Cantidad válida</div>');
                    $inp.closest('form').prepend($ok);
                    setTimeout(function(){ $ok.fadeOut(200,function(){ $ok.remove(); }); }, 900);
                }
            }).fail(function(){
                showError($inp.closest('form'), 'Error de red al comprobar stock.');
                $inp.val($inp.data('prev') || 1);
            });
        }

        // Debounce: espera antes de lanzar la comprobación
        $(document).on('input', 'input[name^="qty["', function(){
            var $inp = $(this);
            var delay = 600; // ms
            var timer = $inp.data('debounceTimer');
            if (timer) clearTimeout(timer);
            $inp.data('debounceTimer', setTimeout(function(){
                var val = parseInt($inp.val(), 10) || 0;
                if (val <= 0) return;
                checkStockForInput($inp, val);
            }, delay));
        });

        // Si el usuario fuerza el cambio (blur / change), comprobamos inmediatamente
        $(document).on('change', 'input[name^="qty["', function(){
            var $inp = $(this);
            var timer = $inp.data('debounceTimer');
            if (timer) { clearTimeout(timer); $inp.removeData('debounceTimer'); }
            var val = parseInt($inp.val(), 10) || 0;
            if (val <= 0) return;
            checkStockForInput($inp, val);
        });

        // Register validation: detecta formulario por campo 'nombres' o action
        var $reg = $('form[action="register_handler.php"]').add( findFormWithFields(['nombres','usuario','contrasena']) ).first();
        if ($reg.length) {
            $reg.on('submit', function(e){
                var nombres = $.trim($reg.find('input[name="nombres"]').val());
                var usuario = $.trim($reg.find('input[name="usuario"]').val());
                var contr = $reg.find('input[name="contrasena"]').val() || '';
                var userRe = /^[a-zA-Z0-9_\-]{3,20}$/;
                if (!nombres) { showError($reg, 'El campo Nombres es obligatorio.'); e.preventDefault(); return false; }
                if (!userRe.test(usuario)) { showError($reg, 'Usuario inválido. Usa 3-20 letras, números, _ o -'); e.preventDefault(); return false; }
                if (contr.length < 6) { showError($reg, 'La contraseña debe tener al menos 6 caracteres.'); e.preventDefault(); return false; }
                return true;
            });
        }

        // Login validation: detecta formulario por action o por campo 'username'
        var $login = $('form[action="auth.php"]').add( findFormWithFields(['username','password']) ).first();
        if ($login.length) {
            $login.on('submit', function(e){
                var uname = $.trim($login.find('input[name="username"]').val());
                var pass = $.trim($login.find('input[name="password"]').val());
                if (!uname || !pass) { showError($login, 'Usuario y contraseña son obligatorios.'); e.preventDefault(); return false; }
                return true;
            });
        }

        // Interceptar formularios de añadir al carrito para validar stock antes de enviar
        $(document).on('submit', 'form[action="add_to_cart.php"]', function(e){
            var $form = $(this);
            if ($form.data('skipStockCheck')) return true; // ya pasamos la validación
            e.preventDefault();
            var category = $.trim($form.find('input[name="category"]').val() || '');
            var name = $.trim($form.find('input[name="name"]').val() || '');
            var qty = parseInt($form.find('input[name="qty"]').val(), 10) || 1;
            if (!category || !name) {
                // si falta info, dejar que el servidor maneje (fallback)
                $form.data('skipStockCheck', true);
                $form.submit();
                return;
            }

            $.ajax({
                url: 'check_stock.php',
                method: 'POST',
                dataType: 'json',
                data: { category: category, name: name, qty: qty }
            }).done(function(res){
                if (!res || !res.ok) {
                    var err = (res && res.error) ? res.error : 'Error al comprobar stock.';
                    showError($form, err);
                    return;
                }
                if (typeof res.available !== 'undefined' && res.available < qty) {
                    showError($form, 'No hay suficientes unidades disponibles. Disponibles: ' + res.available + '.');
                    // actualizar el input a la cantidad disponible
                    $form.find('input[name="qty"]').val(res.available);
                    return;
                }
                // todo ok -> permitir submit una sola vez
                $form.data('skipStockCheck', true);
                $form.submit();
            }).fail(function(){
                showError($form, 'Error de red al comprobar stock. Intenta de nuevo.');
            });
        });
    });
})(jQuery);

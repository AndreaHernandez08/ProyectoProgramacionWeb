document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 Iniciando efectos de iconos de categorías...');
    
    const iconos = document.querySelectorAll('.iconos');
    
    if (iconos.length === 0) {
        console.warn('⚠️ No se encontraron iconos');
        return;
    }
    
    iconos.forEach(icono => {
        // Hacer los iconos clickeables para ir a la página
        icono.style.cursor = 'pointer';
        
        // Al hacer click, redirigir a la página correspondiente
        icono.addEventListener('click', function() {
            const link = this.closest('.Producto').querySelector('a');
            if (link) {
                window.location.href = link.href;
            }
        });
        
        // Efecto de "sacudida" al hacer hover
        icono.addEventListener('mouseenter', function() {
            // Agregar una pequeña vibración
            this.style.animation = 'shake 0.5s ease';
            
            setTimeout(() => {
                this.style.animation = '';
            }, 500);
        });
        
        // Efecto de bounce al hacer click
        icono.addEventListener('click', function(e) {
            this.style.animation = 'clickBounce 0.4s ease';
            
            setTimeout(() => {
                this.style.animation = '';
            }, 400);
        });
        
        // Seguimiento del mouse (parallax leve)
        icono.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            const moveX = x / 10;
            const moveY = y / 10;
            
            const svg = this.querySelector('svg');
            if (svg) {
                svg.style.transform = `translate(${moveX}px, ${moveY}px) scale(1.15) rotate(-8deg)`;
            }
        });
        
        icono.addEventListener('mouseleave', function() {
            const svg = this.querySelector('svg');
            if (svg) {
                svg.style.transform = '';
            }
        });
    });
    
    // Agregar animaciones CSS
    if (!document.getElementById('iconos-animations')) {
        const style = document.createElement('style');
        style.id = 'iconos-animations';
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: scale(1.4) rotate(8deg); }
                25% { transform: scale(1.42) rotate(6deg); }
                50% { transform: scale(1.44) rotate(10deg); }
                75% { transform: scale(1.42) rotate(6deg); }
            }
            
            @keyframes clickBounce {
                0%, 100% { transform: scale(1.4) rotate(8deg); }
                25% { transform: scale(1.5) rotate(-10deg); }
                50% { transform: scale(1.6) rotate(12deg); }
                75% { transform: scale(1.5) rotate(-10deg); }
            }
            
            /* Efecto de ondas al hacer click */
            .iconos::after {
                content: '';
                position: absolute;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                border: 3px solid rgba(102, 126, 234, 0.6);
                opacity: 0;
                transform: scale(1);
                pointer-events: none;
            }
            
            .iconos:active::after {
                animation: ripple 0.6s ease-out;
            }
            
            @keyframes ripple {
                0% {
                    transform: scale(1);
                    opacity: 1;
                }
                100% {
                    transform: scale(1.8);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    console.log(`✅ Efectos aplicados a ${iconos.length} iconos de categorías`);
});

// ===== FUNCIÓN PARA AGREGAR TOOLTIP AL HOVER =====
function agregarTooltips() {
    const productos = document.querySelectorAll('.Producto');
    
    productos.forEach(producto => {
        const icono = producto.querySelector('.iconos');
        const titulo = producto.querySelector('h3');
        
        if (icono && titulo) {
            icono.setAttribute('title', `Ver ${titulo.textContent}`);
            icono.setAttribute('aria-label', `Ir a ${titulo.textContent}`);
        }
    });
}

// Ejecutar cuando cargue
document.addEventListener('DOMContentLoaded', agregarTooltips);
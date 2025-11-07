// Evitar que los enlaces con href="#" recarguen la página
$(document).on('click','a[href="#"]',function(e){ e.preventDefault(); });

const $sidebar = $('#sidebarMenu');
const $backdrop = $('#sidebarBackdrop');

// Funciones para abrir y cerrar el sidebar

function openSidebar(){
  $sidebar.addClass('show');
  $backdrop.addClass('show');
  $('body').addClass('sidebar-open');
}

// Cerrar sidebar

function closeSidebar(){
  $sidebar.removeClass('show');
  $backdrop.removeClass('show');
  $('body').removeClass('sidebar-open');
}

$('#toggleMenu').on('click', openSidebar);
$('#closeMenu, #sidebarBackdrop').on('click', closeSidebar);

// Cerrar si se cambia a ancho >= md
window.matchMedia('(min-width: 768px)').addEventListener('change', e=>{ if(e.matches) closeSidebar(); });
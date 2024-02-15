import 'metismenu'

import 'simplebar';
import ResizeObserver from 'resize-observer-polyfill';
window.ResizeObserver = ResizeObserver;

function initMetisMenu() {
    //metis menu
    $("#side-menu").metisMenu();
}

function initLeftMenuCollapse() {
    $('#vertical-menu-btn').on('click', function (event) {
        event.preventDefault();
        $('body').toggleClass('sidebar-enable');
        if ($(window).width() >= 992) {
            $('body').toggleClass('vertical-collpsed');
        } else {
            $('body').removeClass('vertical-collpsed');
        }
    });
}

function initActiveMenu() {
    // === following js will activate the menu in left side bar based on url ====
    $("#sidebar-menu a").each(function () {
        var pageUrl = window.location.href.split(/[?#]/)[0];
        if (this.href === pageUrl) {
            $(this).addClass("active");
            $(this).parent().addClass("mm-active"); // add active to li of the current link
            $(this).parent().parent().addClass("mm-show");
            $(this).parent().parent().prev().addClass("mm-active"); // add active class to an anchor
            $(this).parent().parent().parent().addClass("mm-active");
            $(this).parent().parent().parent().parent().addClass("mm-show"); // add active to li of the current link
            $(this).parent().parent().parent().parent().parent().addClass("mm-active");
        }
    });
}

function initMenuItemScroll() {
    // focus active menu in left sidebar
    $(document).ready(function () {
        if ($("#sidebar-menu").length > 0 && $("#sidebar-menu .mm-active .active").length > 0) {
            let activeMenu = $("#sidebar-menu .mm-active .active").offset().top;
            if (activeMenu > 300) {
                activeMenu = activeMenu - 300;
                $(".vertical-menu .simplebar-content-wrapper").animate({scrollTop: activeMenu}, "slow");
            }
        }
    });
}

function initHoriMenuActive() {
    $(".navbar-nav a").each(function () {
        const pageUrl = window.location.href.split(/[?#]/)[0];
        if (this.href === pageUrl) {
            $(this).addClass("active");
            $(this).parent().addClass("active");
            $(this).parent().parent().addClass("active");
            $(this).parent().parent().parent().addClass("active");
            $(this).parent().parent().parent().parent().addClass("active");
            $(this).parent().parent().parent().parent().parent().addClass("active");
            $(this).parent().parent().parent().parent().parent().parent().addClass("active");
        }
    });
}

function initRightSidebar() {
    // right side bar toggle
    $('.right-bar-toggle').on('click', function (e) {
        $('body').toggleClass('right-bar-enabled');
    });

    $(document).on('click', 'body', function (e) {
        if ($(e.target).closest('.right-bar-toggle, .right-bar').length > 0) {
            return;
        }

        $('body').removeClass('right-bar-enabled');
        return;
    });
}

function initDropdownMenu() {
    if (document.getElementById("topnav-menu-content")) {
        var elements = document.getElementById("topnav-menu-content").getElementsByTagName("a");
        for (var i = 0, len = elements.length; i < len; i++) {
            elements[i].onclick = function (elem) {
                if (elem.target.getAttribute("href") === "#") {
                    elem.target.parentElement.classList.toggle("active");
                    elem.target.nextElementSibling.classList.toggle("show");
                }
            }
        }
        window.addEventListener("resize", updateMenu);
    }
}

function updateMenu() {
    var elements = document.getElementById("topnav-menu-content").getElementsByTagName("a");
    for (var i = 0, len = elements.length; i < len; i++) {
        if (elements[i].parentElement.getAttribute("class") === "nav-item dropdown active") {
            elements[i].parentElement.classList.remove("active");
            elements[i].nextElementSibling.classList.remove("show");
        }
    }
}

window.initComponents = function() {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });
}

function initSettings() {
    if (window.sessionStorage) {
        const alreadyVisited = sessionStorage.getItem("is_visited");
        if (!alreadyVisited) {
            sessionStorage.setItem("is_visited", "light-mode-switch");
        } else {
            $(".right-bar input:checkbox").prop('checked', false);
            $("#" + alreadyVisited).prop('checked', true);
            updateThemeSetting(alreadyVisited);
        }
    }
    $("#light-mode-switch, #dark-mode-switch, #rtl-mode-switch, #dark-rtl-mode-switch").on("change", function (e) {
        updateThemeSetting(e.target.id);
    });

    // show password input value
    $("#password-addon").on('click', function () {
        if ($(this).siblings('input').length > 0) {
            $(this).siblings('input').attr('type') === "password" ? $(this).siblings('input').attr('type', 'input') : $(this).siblings('input').attr('type', 'password');
        }
    })
}

function updateThemeSetting(id) {
    if ($("#light-mode-switch").prop("checked") == true && id === "light-mode-switch") {
        $("html").removeAttr("dir");
        $("#dark-mode-switch").prop("checked", false);
        $("#rtl-mode-switch").prop("checked", false);
        $("#dark-rtl-mode-switch").prop("checked", false);
        $("#bootstrap-style").attr('href', 'assets/css/bootstrap.min.css');
        $("#app-style").attr('href', 'assets/css/app.min.css');
        sessionStorage.setItem("is_visited", "light-mode-switch");
    } else if ($("#dark-mode-switch").prop("checked") == true && id === "dark-mode-switch") {
        $("html").removeAttr("dir");
        $("#light-mode-switch").prop("checked", false);
        $("#rtl-mode-switch").prop("checked", false);
        $("#dark-rtl-mode-switch").prop("checked", false);
        $("#bootstrap-style").attr('href', 'assets/css/bootstrap-dark.min.css');
        $("#app-style").attr('href', 'assets/css/app-dark.min.css');
        sessionStorage.setItem("is_visited", "dark-mode-switch");
    } else if ($("#rtl-mode-switch").prop("checked") == true && id === "rtl-mode-switch") {
        $("#light-mode-switch").prop("checked", false);
        $("#dark-mode-switch").prop("checked", false);
        $("#dark-rtl-mode-switch").prop("checked", false);
        $("#bootstrap-style").attr('href', 'assets/css/bootstrap.rtl.css');
        $("#app-style").attr('href', 'assets/css/app.rtl.css');
        $("html").attr("dir", 'rtl');
        sessionStorage.setItem("is_visited", "rtl-mode-switch");
    } else if ($("#dark-rtl-mode-switch").prop("checked") == true && id === "dark-rtl-mode-switch") {
        $("#light-mode-switch").prop("checked", false);
        $("#rtl-mode-switch").prop("checked", false);
        $("#dark-mode-switch").prop("checked", false);
        $("#bootstrap-style").attr('href', 'assets/css/bootstrap-dark.rtl.css');
        $("#app-style").attr('href', 'assets/css/app-dark.rtl.css');
        $("html").attr("dir", 'rtl');
        sessionStorage.setItem("is_visited", "dark-rtl-mode-switch");
    }
}

function init() {
    initMetisMenu();
    initLeftMenuCollapse();
    initActiveMenu();
    initMenuItemScroll();
    initHoriMenuActive();
    initRightSidebar();
    initDropdownMenu();
    initComponents();
    initSettings();
}

init();

import Swal from 'sweetalert2'

window.openSweetAlert = function (type = 'warning', url, msg = null, datatable_id = null){
    Swal.fire({
        title: "Are you sure?",
        text: msg ?? 'You want delete this item',
        icon: type,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: "No",
        allowOutsideClick: false,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch(url)
                .then(res => {
                    if (!res.ok) {
                        throw new Error(res.statusText)
                    }
                    return res.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
        },
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                text: `${result.value.message}`,
                icon: 'success',
                confirmButtonText: 'Ok',
                allowOutsideClick: false,
            }).then(() => {
                if(datatable_id) {
                    LaravelDataTables[datatable_id].ajax.reload()
                } else {
                    location.reload()
                }
            })
        }
    })
}



window.refreshDatatable = function () {
    for (const key in LaravelDataTables) {
        LaravelDataTables[key].ajax.reload()
    }
}

import ApexCharts from 'apexcharts'
window.apexcharts = function (selector, options) {
    return new ApexCharts(selector, options)
}



import select2 from 'select2';
select2(jQuery);
// default select configuration
$('.select2-multiple').select2({
    multiple: true,
});

$('.select2-multiple-disable-search').on('select2:opening select2:closing', function( event ) {
    var $searchfield = $(this).parent().find('.select2-search__field');
    $searchfield.prop('disabled', true);
});

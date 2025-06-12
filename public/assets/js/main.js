/**
* Template Name: NiceAdmin
* Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
* Updated: Apr 20 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function () {
    "use strict";

    /**
     * Easy selector helper function
     */
    const select = (el, all = false) => {
        el = el.trim()
        if (all) {
            return [...document.querySelectorAll(el)]
        } else {
            return document.querySelector(el)
        }
    }

    /**
     * Easy event listener function
     */
    const on = (type, el, listener, all = false) => {
        if (all) {
            select(el, all).forEach(e => e.addEventListener(type, listener))
        } else {
            select(el, all).addEventListener(type, listener)
        }
    }

    /**
     * Easy on scroll event listener
     */
    const onscroll = (el, listener) => {
        el.addEventListener('scroll', listener)
    }

    /**
     * Sidebar toggle
     */
    if (select('.toggle-sidebar-btn')) {
        on('click', '.toggle-sidebar-btn', function (e) {
            select('body').classList.toggle('toggle-sidebar')
        })
    }

    /**
     * Search bar toggle
     */
    if (select('.search-bar-toggle')) {
        on('click', '.search-bar-toggle', function (e) {
            select('.search-bar').classList.toggle('search-bar-show')
        })
    }

    /**
     * Navbar links active state on scroll
     */
    let navbarlinks = select('#navbar .scrollto', true)
    const navbarlinksActive = () => {
        let position = window.scrollY + 200
        navbarlinks.forEach(navbarlink => {
            if (!navbarlink.hash) return
            let section = select(navbarlink.hash)
            if (!section) return
            if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
                navbarlink.classList.add('active')
            } else {
                navbarlink.classList.remove('active')
            }
        })
    }
    window.addEventListener('load', navbarlinksActive)
    onscroll(document, navbarlinksActive)

    /**
     * Toggle .header-scrolled class to #header when page is scrolled
     */
    let selectHeader = select('#header')
    if (selectHeader) {
        const headerScrolled = () => {
            if (window.scrollY > 100) {
                selectHeader.classList.add('header-scrolled')
            } else {
                selectHeader.classList.remove('header-scrolled')
            }
        }
        window.addEventListener('load', headerScrolled)
        onscroll(document, headerScrolled)
    }

    /**
     * Back to top button
     */
    let backtotop = select('.back-to-top')
    if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY > 100) {
                backtotop.classList.add('active')
            } else {
                backtotop.classList.remove('active')
            }
        }
        window.addEventListener('load', toggleBacktotop)
        onscroll(document, toggleBacktotop)
    }

    /**
     * Initiate tooltips
     */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    /**
     * Initiate quill editors
     */
    if (select('.quill-editor-default')) {
        new Quill('.quill-editor-default', {
            theme: 'snow'
        });
    }

    if (select('.quill-editor-bubble')) {
        new Quill('.quill-editor-bubble', {
            theme: 'bubble'
        });
    }

    if (select('.quill-editor-full')) {
        new Quill(".quill-editor-full", {
            modules: {
                toolbar: [
                    [{
                        font: []
                    }, {
                        size: []
                    }],
                    ["bold", "italic", "underline", "strike"],
                    [{
                        color: []
                    },
                    {
                        background: []
                    }
                    ],
                    [{
                        script: "super"
                    },
                    {
                        script: "sub"
                    }
                    ],
                    [{
                        list: "ordered"
                    },
                    {
                        list: "bullet"
                    },
                    {
                        indent: "-1"
                    },
                    {
                        indent: "+1"
                    }
                    ],
                    ["direction", {
                        align: []
                    }],
                    ["link", "image", "video"],
                    ["clean"]
                ]
            },
            theme: "snow"
        });
    }



    const useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isSmallScreen = window.matchMedia('(max-width: 1023.5px)').matches;

    tinymce.init({
        selector: 'textarea.tinymce-editor',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
        editimage_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        link_list: [{
            title: 'My page 1',
            value: 'https://www.tiny.cloud'
        },
        {
            title: 'My page 2',
            value: 'http://www.moxiecode.com'
        }
        ],
        image_list: [{
            title: 'My page 1',
            value: 'https://www.tiny.cloud'
        },
        {
            title: 'My page 2',
            value: 'http://www.moxiecode.com'
        }
        ],
        image_class_list: [{
            title: 'None',
            value: ''
        },
        {
            title: 'Some class',
            value: 'class-name'
        }
        ],
        importcss_append: true,
        file_picker_callback: (callback, value, meta) => {

            if (meta.filetype === 'file') {
                callback('https://www.google.com/logos/google.jpg', {
                    text: 'My text'
                });
            }


            if (meta.filetype === 'image') {
                callback('https://www.google.com/logos/google.jpg', {
                    alt: 'My alt text'
                });
            }


            if (meta.filetype === 'media') {
                callback('movie.mp4', {
                    source2: 'alt.ogg',
                    poster: 'https://www.google.com/logos/google.jpg'
                });
            }
        },
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_class: 'mceNonEditable',
        toolbar_mode: 'sliding',
        contextmenu: 'link image table',
        skin: useDarkMode ? 'oxide-dark' : 'oxide',
        content_css: useDarkMode ? 'dark' : 'default',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
    });


    var needsValidation = document.querySelectorAll('.needs-validation')

    Array.prototype.slice.call(needsValidation)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })


    const datatables = select('.datatable', true)
    datatables.forEach(datatable => {
        new simpleDatatables.DataTable(datatable, {
            perPageSelect: [5, 10, 15, ["All", -1]],
            columns: [{
                select: 2,
                sortSequence: ["desc", "asc"]
            },
            {
                select: 3,
                sortSequence: ["desc"]
            },
            {
                select: 4,
                cellClass: "green",
                headerClass: "red"
            }
            ]
        });
    })




    const mainContainer = select('#main');
    if (mainContainer) {
        setTimeout(() => {
            new ResizeObserver(function () {
                select('.echart', true).forEach(getEchart => {
                    echarts.getInstanceByDom(getEchart).resize();
                })
            }).observe(mainContainer);
        }, 200);
    }

})();



document.addEventListener('DOMContentLoaded', function () {
    const toastElCategory = document.getElementById('statusToastCategory');
    const toastCategory = new bootstrap.Toast(toastElCategory);

    document.querySelectorAll('.toggle-switch-status-category').forEach(function (switchEl) {
        switchEl.addEventListener('change', function () {
            const categoryId = this.dataset.id;
            const isChecked = this.checked;
            const self = this;

            fetch(`/dashboard/categories/${categoryId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: isChecked ? 1 : 0 })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById('toastMessageCategory').textContent = 'Status updated to ' + data.new_status;
                        toastCategory.show();
                    } else {
                        alert('Error occurred while toggling status.');
                        self.checked = !isChecked;
                    }
                })
                .catch(() => {
                    alert('Error occurred while toggling status.');
                    self.checked = !isChecked;
                });
        });
    });
});



document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const model = this.getAttribute('data-model');
            const id = this.getAttribute('data-id');
            const rowId = this.getAttribute('data-row-id');

            fetch(`/dashboard/${model}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {

                    const row = document.getElementById(rowId);
                    if (row) row.remove();


                    const alertHtml = `
                    <div id="autoDismissAlert" class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                    document.querySelector('.pagetitle').insertAdjacentHTML('afterend', alertHtml);


                    setTimeout(() => {
                        const alert = document.getElementById('autoDismissAlert');
                        if (alert) {
                            alert.classList.remove('show');
                            alert.classList.add('fade-out');
                            setTimeout(() => alert.remove(), 500);
                        }
                    }, 1500);
                })
                .catch(error => {
                    console.error('Delete failed', error);
                });
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const alert = document.getElementById('sessionAlert');
    if (!alert) return;


    setTimeout(() => {
        alert.classList.add('fade-out');

        setTimeout(() => {
            alert.remove();
        }, 500);
    }, 1500);
});



document.addEventListener('DOMContentLoaded', function () {
    const notificationDropdown = document.querySelector('.nav-item.dropdown');

    if (notificationDropdown) {
        notificationDropdown.addEventListener('show.bs.dropdown', function () {
            const menu = this.querySelector('.dropdown-menu');
            menu.classList.add('show');
        });

        notificationDropdown.addEventListener('hide.bs.dropdown', function () {
            const menu = this.querySelector('.dropdown-menu');
            menu.classList.remove('show');
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const toastElUser = document.getElementById('statusToastUser');
    const toastUser = new bootstrap.Toast(toastElUser);

    document.querySelectorAll('.toggle-switch-status-user').forEach(function (switchEl) {
        switchEl.addEventListener('change', function () {
            const userId = this.getAttribute('data-id');
            const newStatus = this.checked ? 'active' : 'block';
            const self = this;

            fetch(`/dashboard/users/${userId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
                .then(async response => {
                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`Server error: ${response.status} - ${errorText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status) {
                        document.getElementById('toastMessageUser').textContent = `User status is now ${data.new_status}`;
                        toastUser.show();
                    } else {
                        alert('Error occurred while toggling status.');
                        self.checked = !self.checked;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(`Error occurred while toggling status: ${error.message}`);
                    self.checked = !self.checked;
                });
        });
    });
});





document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('statusToastFood');
    const toast = new bootstrap.Toast(toastEl);

    document.querySelectorAll('.toggle-switch-status-food').forEach(function (switchEl) {
        switchEl.addEventListener('change', function () {
            const foodId = this.getAttribute('data-id');
            const newStatus = this.checked ? 'active' : 'inactive';
            const self = this;

            fetch(`/dashboard/foods/${foodId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
                .then(async response => {
                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`Server error: ${response.status} - ${errorText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status) {
                        document.getElementById('toastMessageFood').textContent = `Food status is now ${data.new_status}`;
                        toast.show();
                    } else {
                        alert('Error occurred while toggling status.');
                        self.checked = !self.checked;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(`Error occurred while toggling status: ${error.message}`);
                    self.checked = !self.checked;
                });
        });
    });
});


setTimeout(() => {
    const errorAlert = document.getElementById('errorAlert');
    if (errorAlert) {
        errorAlert.classList.add('fade-out');
        setTimeout(() => errorAlert.remove(), 600);
    }

    const sessionAlert = document.getElementById('sessionAlert');
    if (sessionAlert) {
        sessionAlert.classList.add('fade-out');
        setTimeout(() => sessionAlert.remove(), 600);
    }
}, 2000);

































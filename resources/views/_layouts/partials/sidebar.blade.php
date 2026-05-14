<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li class="menu-title border-top-0 pt-2">@lang('Main Menu')</li>
            

            <li>
                <a href="/">
                    <i class="flaticon-layout text-primary"></i>
                    <span class="nav-text">@lang('Dashboard')</span>
                </a>
            </li>

            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <i class="fa-solid fa-file-export text-success"></i>
                    <span class="nav-text">@lang('Izlazne fakture')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li><a class="dz-active" href="{{route('customer-invoices.index')}}">@lang('Lista fakture')</a></li>
                    <li><a class="dz-active" href="{{route('customer-invoices.reports')}}">@lang('Ukupan Izvjestaj')</a></li>
                    <li><a class="dz-active" href="{{route('customer-invoices.create')}}">+ @lang('Kreiraj fakturu')</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <i class="fa-solid fa-file-import text-warning"></i>
                    <span class="nav-text">@lang('Ulazne fakture')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li><a class="dz-active" href="{{route('supplier-invoices.index')}}">@lang('Lista fakture')</a></li>
                    <li><a class="dz-active" href="{{route('supplier-invoices.reports')}}">@lang('Ukupan Izvjestaj')</a></li>
                    <li><a class="dz-active" href="{{route('supplier-invoices.create')}}">+ @lang('Kreiraj fakturu')</a></li>
                </ul>
            </li>

            <hr>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <i class="fas fa-building"></i>
                    <span class="nav-text">@lang('Firme')</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">@lang('Firme')</li>
                    <li><a class="dz-active" href="{{ route("firme.index") }}">@lang('Lista firmi')</a></li>
                    <li><a class="dz-active" href="{{ route('firme.create') }}">+ @lang('Kreiraj firmu')</a></li>
                </ul>
            </li>
            <hr>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <i class="fas fa-file-invoice-dollar text-primary"></i>
                    <span class="nav-text">@lang('Racuni')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li class="nav-text-icon-toggle">Racuni</li>
                    <li><a class="dz-active" href="{{ route("rechnung.index") }}">@lang('Lista racuna')</a></li>
                    <li><a class="dz-active" href="{{ route('rechnung.index') }}?openModal=1">+ @lang('Kreiraj racun')</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <i class="flaticon-notes"></i>
                    <span class="nav-text">@lang('Ponude')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li class="nav-text-icon-toggle">@lang('Ponude')</li>
                    <li><a class="dz-active" href="{{ route("angebote.index") }}">@lang('Lista ponuda')</a></li>
                    <li><a class="dz-active" href="{{ route('angebote.index') }}?openModal=1">+ @lang('Kreiraj ponudu')</a></li>
                </ul>
            </li>


            {{-- <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-layout"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Dashboard</li>
                    <li><a class="dz-active" href="files/index.html">Dashboard Light</a></li>
                    <li><a class="dz-active" href="files/index-2.html">Dashboard Dark</a></li>
                    <li><a class="dz-active" href="files/wallet.html">Wallet</a></li>
                    <li><a class="dz-active" href="files/invoices.html">Invoices</a></li>
                    <li><a class="dz-active" href="files/create-invoices.html">Create Invoice</a></li>
                    <li><a class="dz-active" href="files/card-center.html">Card Center</a></li>
                    <li><a class="dz-active" href="files/transaction-details.html">Transaction</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon flaticon-user-1"></i>
                    <span class="nav-text">Profile</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="dz-active" href="files/profile/overview.html">Overview</a></li>
                    <li><a class="dz-active" href="files/profile/projects.html">Projects</a></li>
                    <li><a class="dz-active" href="files/profile/projects-details.html">Projects Details</a></li>
                    <li><a class="dz-active" href="files/profile/campaigns.html">Campaigns</a></li>
                    <li><a class="dz-active" href="files/profile/documents.html">Documents</a></li>
                    <li><a class="dz-active" href="files/profile/followers.html">Followers</a></li>
                    <li><a class="dz-active" href="files/profile/activity.html">Activity</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon flaticon-app"></i>
                    <span class="nav-text">Account</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="dz-active" href="files/account/overview.html">Overview</a></li>
                    <li><a class="dz-active" href="files/account/settings.html">Settings</a></li>
                    <li><a class="dz-active" href="files/account/security.html">Security</a></li>
                    <li><a class="dz-active" href="files/account/activity.html">Activity</a></li>
                    <li><a class="dz-active" href="files/account/billing.html">Billing</a></li>
                    <li><a class="dz-active" href="files/account/statements.html">Statements</a></li>
                    <li><a class="dz-active" href="files/account/referrals.html">Referrals</a></li>
                    <li><a class="dz-active" href="files/account/api-keys.html">Api keys</a></li>
                    <li><a class="dz-active" href="files/account/logs.html">Logs</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-web-1"></i>
                    <span class="nav-text">CMS</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">CMS</li>
                    <li><a class="dz-active" href="files/content.html">Content</a></li>
                    <li><a class="dz-active" href="files/content-add.html">Add Content</a></li>
                    <li><a class="dz-active" href="files/menu.html">Menus</a></li>
                    <li><a class="dz-active" href="files/email-template.html">Email Template</a></li>
                    <li><a class="dz-active" href="files/add-email.html">Add Email</a></li>
                    <li><a class="dz-active" href="files/blog.html">Blog</a></li>
                    <li><a class="dz-active" href="files/add-blog.html">Add Blog</a></li>
                    <li><a class="dz-active" href="files/blog-category.html">Blog Category</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-chip"></i>
                    <span class="nav-text">AIKit</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">AIKit</li>
                    <li><a class="dz-active" href="files/auto-write.html">Auto Writer</a></li>
                    <li><a class="dz-active" href="files/scheduled.html">Scheduler</a></li>
                    <li><a class="dz-active" href="files/repurpose.html">Repurpose</a></li>
                    <li><a class="dz-active" href="files/rss.html">RSS</a></li>
                    <li><a class="dz-active" href="files/chatbot.html">Chatbot</a></li>
                    <li><a class="dz-active" href="files/fine-tune-models.html">Fine-tune Models</a></li>
                    <li><a class="dz-active" href="files/prompt.html">AI Menu Prompts</a></li>
                    <li><a class="dz-active" href="files/setting.html">Settings</a></li>
                    <li><a class="dz-active" href="files/import.html">Export/Import Settings</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-blog"></i>
                    <span class="nav-text">Blog</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Blog</li>
                    <li><a class="dz-active" href="files/blog-post.html">Blog Post</a></li>
                    <li><a class="dz-active" href="files/blog-home.html">Blog Home</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-user"></i>
                    <span class="nav-text">User</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">User</li>
                    <li><a class="dz-active" href="files/app-profile.html">Profile</a></li>
                    <li><a class="dz-active" href="files/edit-profile.html">Edit Profile</a></li>
                    <li><a class="dz-active" href="files/post-details.html">Post Details</a></li>


                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-bag"></i>
                    <span class="nav-text">jobs</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">jobs</li>
                    <li><a class="dz-active" href="files/job-view.html">Job View</a></li>
                    <li><a class="dz-active" href="files/job-application.html">Job Application</a></li>
                    <li><a class="dz-active" href="files/apply-job.html">Apply Job</a></li>
                    <li><a class="dz-active" href="files/new-job.html">New Job</a></li>

                </ul>
            </li>

            <li><a href="files/pricing.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-price-tag"></i>
                    <span class="nav-text">Pricing</span>
                </a>
            </li>
            <li class="menu-title">Apps</li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-chat"></i>
                    <span class="nav-text">Chat</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Chat</li>
                    <li><a class="dz-active" href="files/chat-home.html">Chat Home</a></li>
                    <li><a class="dz-active" href="files/chat-modal.html">Chat Modal</a></li>

                </ul>
            </li>
            <li><a href="files/kanban.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-kanban"></i>
                    <span class="nav-text">Kanban</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">

                    <i class="flaticon-email"></i>
                    <span class="nav-text">Email</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Email</li>
                    <li><a class="dz-active" href="files/email-compose.html">Compose</a></li>
                    <li><a class="dz-active" href="files/email-inbox.html">Inbox</a></li>
                    <li><a class="dz-active" href="files/email-read.html">Read</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-shopping-bag"></i>

                    <span class="nav-text">Ecommerce</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Ecommerce</li>
                    <li><a class="dz-active" href="files/ecommerce-dashboard.html">Dashboard</a></li>
                    <li><a class="dz-active" href="files/ecommerce-setting.html">Setting</a></li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Categories</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Categories</li>
                            <li><a class="dz-active" href="files/category-table.html">Category Table</a></li>
                            <li><a class="dz-active" href="files/add-categary.html">Add Category</a></li>
                            <li><a class="dz-active" href="files/edit-categary.html">Edit Category</a></li>
                        </ul>
                    </li>

                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Products</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Products</li>
                            <li><a class="dz-active" href="files/product-table.html">Product Table</a></li>
                            <li><a class="dz-active" href="files/add-product.html">Add product</a></li>
                            <li><a class="dz-active" href="files/edit-product.html">Edit Product</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Shop</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Shop</li>
                            <li><a class="dz-active" href="files/ecom-product-grid.html">Product Grid</a></li>
                            <li><a class="dz-active" href="files/ecom-product-list.html">Product List</a></li>
                            <li><a class="dz-active" href="files/ecom-product-detail.html">Product Details</a></li>
                            <li><a class="dz-active" href="files/ecom-product-order.html">Order</a></li>
                            <li><a class="dz-active" href="files/ecom-checkout.html">Checkout</a></li>
                            <li><a class="dz-active" href="files/ecom-invoice.html">Invoice</a></li>
                            <li><a class="dz-active" href="files/ecom-customers.html">Customers</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-rocket"></i>
                    <span class="nav-text">Projects</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Projects</li>
                    <li><a class="dz-active" href="files/project-list.html">Project List</a></li>
                    <li><a class="dz-active" href="files/project-card.html">Project Card</a></li>
                    <li><a class="dz-active" href="files/add-project.html">Add Project</a></li>

                </ul>
            </li>
            <li><a href="files/note.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-notes"></i>
                    <span class="nav-text">Notes</span>
                </a>
            </li>
            <li><a href="files/task.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-checkmark"></i>
                    <span class="nav-text">Task</span>
                </a>
            </li>
            <li><a href="files/file-manger.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-approved"></i>
                    <span class="nav-text">File Manager</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-phone-book"></i>
                    <span class="nav-text">Contacts</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Contacts</li>
                    <li><a class="dz-active" href="files/contact-list.html">Contact List</a></li>
                    <li><a class="dz-active" href="files/contact-card.html">Contact Card</a></li>
                </ul>
            </li>

            <li><a href="files/app-calender.html" class="" aria-expanded="false">
                    <i class="flaticon-calendar-2"></i>
                    <span class="nav-text">Calender</span>

                </a>
            </li>
            <li class="menu-title">Components</li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-bar-chart"></i>
                    <span class="nav-text">Charts</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Charts</li>
                    <li><a class="dz-active" href="files/apex.html">Apex Chart</a></li>
                    <li><a class="dz-active" href="files/chart-flot.html">Flot</a></li>
                    <li><a class="dz-active" href="files/chart-morris.html">Morris</a></li>
                    <li><a class="dz-active" href="files/chart-chartjs.html">Chartjs</a></li>
                    <li><a class="dz-active" href="files/chart-chartist.html">Chartist</a></li>
                    <li><a class="dz-active" href="files/chart-sparkline.html">Sparkline</a></li>
                    <li><a class="dz-active" href="files/chart-peity.html">Peity</a></li>

                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-web"></i>
                    <span class="nav-text">Bootstrap</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Bootstrap</li>
                    <li><a class="dz-active" href="files/ui-accordion.html">Accordion</a></li>
                    <li><a class="dz-active" href="files/ui-alert.html">Alert</a></li>
                    <li><a class="dz-active" href="files/ui-badge.html">Badge</a></li>
                    <li><a class="dz-active" href="files/ui-button.html">Button</a></li>
                    <li><a class="dz-active" href="files/ui-modal.html">Modal</a></li>
                    <li><a class="dz-active" href="files/ui-button-group.html">Button Group</a></li>
                    <li><a class="dz-active" href="files/ui-list-group.html">List Group</a></li>
                    <li><a class="dz-active" href="files/ui-card.html">Cards</a></li>
                    <li><a class="dz-active" href="files/ui-carousel.html">Carousel</a></li>
                    <li><a class="dz-active" href="files/ui-dropdown.html">Dropdown</a></li>
                    <li><a class="dz-active" href="files/ui-popover.html">Popover</a></li>
                    <li><a class="dz-active" href="files/ui-progressbar.html">Progressbar</a></li>
                    <li><a class="dz-active" href="files/ui-tab.html">Tab</a></li>
                    <li><a class="dz-active" href="files/ui-typography.html">Typography</a></li>
                    <li><a class="dz-active" href="files/ui-pagination.html">Pagination</a></li>
                    <li><a class="dz-active" href="files/ui-grid.html">Grid</a></li>

                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-puzzle"></i>
                    <span class="nav-text">Plugins</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Plugins</li>
                    <li><a class="dz-active" href="files/uc-select2.html">Select 2</a></li>
                    <li><a class="dz-active" href="files/uc-nestable.html">Nestedable</a></li>
                    <li><a class="dz-active" href="files/uc-noui-slider.html">Noui Slider</a></li>
                    <li><a class="dz-active" href="files/uc-tree.html">Tree View</a></li>
                    <li><a class="dz-active" href="files/uc-star-rating.html">Star Rating</a></li>
                    <li><a class="dz-active" href="files/uc-drag-and-drop.html">Drag And Drop</a></li>
                    <li><a class="dz-active" href="files/uc-media-player.html">Media Player</a></li>
                    <li><a class="dz-active" href="files/uc-sweetalert.html">Sweet Alert</a></li>
                    <li><a class="dz-active" href="files/uc-toastr.html">Toastr</a></li>
                    <li><a class="dz-active" href="files/map-jqvmap.html">Jqv Map</a></li>
                    <li><a class="dz-active" href="files/uc-lightgallery.html">Light Gallery</a></li>
                </ul>
            </li>
            <li><a href="files/widget-basic.html" class="" aria-expanded="false">
                    <i class="flaticon-app"></i>
                    <span class="nav-text">Widget</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-registration"></i>
                    <span class="nav-text">Forms</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Forms</li>
                    <li><a class="dz-active" href="files/form-element.html">Form Elements</a></li>
                    <li><a class="dz-active" href="files/form-wizard.html">Wizard</a></li>
                    <li><a class="dz-active" href="files/form-ckeditor.html">CkEditor</a></li>
                    <li><a class="dz-active" href="files/form-pickers.html">Pickers</a></li>
                    <li><a class="dz-active" href="files/form-validation.html">Form Validate</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-grid"></i>
                    <span class="nav-text">Table</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Table</li>
                    <li><a class="dz-active" href="files/table-bootstrap-basic.html">Bootstrap</a></li>
                    <li><a class="dz-active" href="files/table-datatable-basic.html">Datatable</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-file"></i>
                    <span class="nav-text">Pages</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li><a class="dz-active" href="files/page-login.html">Login</a></li>
                    <li><a class="dz-active" href="files/page-register.html">Register</a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Error</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Pages</li>
                            <li><a class="dz-active" href="files/page-error-400.html">Error 400</a></li>
                            <li><a class="dz-active" href="files/page-error-403.html">Error 403</a></li>
                            <li><a class="dz-active" href="files/page-error-404.html">Error 404</a></li>
                            <li><a class="dz-active" href="files/page-error-500.html">Error 500</a></li>
                            <li><a class="dz-active" href="files/page-error-503.html">Error 503</a></li>
                        </ul>
                    </li>
                    <li><a class="dz-active" href="files/page-lock-screen.html">Lock Screen</a></li>
                    <li><a class="dz-active" href="files/empty-page.html">Empty Page</a></li>
                </ul>
            </li> --}}

        </ul>

        <div style="display: none;" class="copyright">
            <p><strong>Kubayar Invoicing Admin Dashboard</strong> © <span class="current-year">2024</span> All
                Rights Reserved</p>
            <p class="fs-12">Made with <span class="heart"></span> by DexignZone</p>
        </div>
    </div>
</div>
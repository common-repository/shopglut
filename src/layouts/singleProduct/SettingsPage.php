<?php
namespace Shopglut\layouts\singleProduct;

class SettingsPage {
	public function __construct() {
	}

	public function loadSingleproductEditor() {
		$this->singleproductEditor();

	}

	public function singleproductEditor() {
		?>
<div id="nta-web-page-builder">
    <section class="yaymail-layout">
        <div class="nta-web-spin-preview-email yaymail-spin-nested-loading">
            <div class="yaymail-spin-container">
                <section class="yaymail-layout yaymail-layout-has-sider">
                    <aside class="nta-web-siderbar yaymail-layout-sider yaymail-layout-sider-dark"
                        style="flex: 0 0 200px; max-width: 200px; min-width: 200px; width: 200px; margin-left: 0px;">
                        <div class="yaymail-layout-sider-children">
                            <div class="shopg-editor-heading">
                                <div class="shopg-editor-heading-title">
                                    <h5 class="nta-web-name-control">Single Layout Customizer</h5>
                                </div>
                                <div class="icon">
                                    <i data-v-6096d9a2="" aria-label="icon: appstore" tabindex="-1"
                                        class="nta-web-icon anticon anticon-appstore"><svg viewBox="64 64 896 896"
                                            data-icon="appstore" width="1em" height="1em" fill="currentColor"
                                            aria-hidden="true" focusable="false" class="">
                                            <path
                                                d="M464 144H160c-8.8 0-16 7.2-16 16v304c0 8.8 7.2 16 16 16h304c8.8 0 16-7.2 16-16V160c0-8.8-7.2-16-16-16zm-52 268H212V212h200v200zm452-268H560c-8.8 0-16 7.2-16 16v304c0 8.8 7.2 16 16 16h304c8.8 0 16-7.2 16-16V160c0-8.8-7.2-16-16-16zm-52 268H612V212h200v200zM464 544H160c-8.8 0-16 7.2-16 16v304c0 8.8 7.2 16 16 16h304c8.8 0 16-7.2 16-16V560c0-8.8-7.2-16-16-16zm-52 268H212V612h200v200zm452-268H560c-8.8 0-16 7.2-16 16v304c0 8.8 7.2 16 16 16h304c8.8 0 16-7.2 16-16V560c0-8.8-7.2-16-16-16zm-52 268H612V612h200v200z">
                                            </path>
                                        </svg></i>
                                </div>
                            </div>
                            <div class="nta-web-setting">
                                <div class="components-settings-head">
                                    <div class="components-settings-data">
                                        <i id="component-home" class="fa-solid fa-arrow-left"></i>
                                        Edit <span class="component-name"> Settings</span>
                                    </div>
                                </div>
                                <div class="shopglut-nav-content">
                                    <div class="nta-web-nav-content yaymail-row-flex yaymail-row-flex-middle">
                                        <div class="nta-web-tab-wrap yaymail-col yaymail-col-12">
                                            <p class="nta-web-tab-control activeTab">ELEMENTS</p>
                                        </div>
                                        <div class="nta-web-tab-wrap yaymail-col yaymail-col-12">
                                            <p class="nta-web-tab-control">SETTINGS</p>
                                        </div>
                                    </div>

                                </div>

                                <div class="single-product-components"
                                    style="height: 100%; width: 100%; padding: 0px; position: relative; overflow: hidden;">
                                    <div class="__panel"
                                        style="position: relative; box-sizing: border-box; height: 100%; overflow: hidden scroll; margin-right: -17px;">
                                        <div class="__view"
                                            style="position: relative; box-sizing: border-box; min-width: 100%; min-height: 100%; width: 100%;">
                                            <div class="nta-web-wrap-element" style="">
                                                <div class="yaymail-row-flex yaymail-row-flex-center">
                                                    <div class="yaymail-col yaymail-col-22">
                                                        <span
                                                            class="nta-web-seach yaymail-input-search yaymail-input-affix-wrapper">
                                                            <input placeholder="Search element" type="text"
                                                                class="yaymail-input">
                                                            <span class="yaymail-input-suffix">
                                                                <i aria-label="icon: search" tabindex="-1"
                                                                    class="anticon anticon-search yaymail-input-search-icon">
                                                                    <svg viewBox="64 64 896 896" data-icon="search"
                                                                        width="1em" height="1em" fill="currentColor"
                                                                        aria-hidden="true" focusable="false" class="">
                                                                        <path
                                                                            d="M909.6 854.5L649.9 594.8C690.2 542.7 712 479 712 412c0-80.2-31.3-155.4-87.9-212.1-56.6-56.7-132-87.9-212.1-87.9s-155.5 31.3-212.1 87.9C143.2 256.5 112 331.8 112 412c0 80.1 31.3 155.5 87.9 212.1C256.5 680.8 331.8 712 412 712c67 0 130.6-21.8 182.7-62l259.7 259.6a8.2 8.2 0 0 0 11.6 0l43.6-43.5a8.2 8.2 0 0 0 0-11.6zM570.4 570.4C528 612.7 471.8 636 412 636s-116-23.3-158.4-65.6C211.3 528 188 471.8 188 412s23.3-116.1 65.6-158.4C296 211.3 352.2 188 412 188s116.1 23.2 158.4 65.6S636 352.2 636 412s-23.3 116.1-65.6 158.4z">
                                                                        </path>
                                                                    </svg>
                                                                </i>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="nta-web-panel-elements-categories">
                                                    <div class="nta-web-title-wrap nta-web-panel-el-action">
                                                        <p class="nta-web-title">Basic</p>
                                                        <!---->
                                                        <i aria-label="icon: up"
                                                            class="nta-web-icon-down anticon anticon-up">
                                                            <svg viewBox="64 64 896 896" data-icon="up" width="1em"
                                                                height="1em" fill="currentColor" aria-hidden="true"
                                                                focusable="false" class="">
                                                                <path
                                                                    d="M890.5 755.3L537.9 269.2c-12.8-17.6-39-17.6-51.7 0L133.5 755.3A8 8 0 0 0 140 768h75c5.1 0 9.9-2.5 12.9-6.6L512 369.8l284.1 391.6c3 4.1 7.8 6.6 12.9 6.6h75c6.5 0 10.3-7.4 6.5-12.7z">
                                                                </path>
                                                            </svg>
                                                        </i>
                                                    </div>
                                                    <div
                                                        class="nta-web-container-element-basicEl nta-web-container-element">
                                                        <div class="component nta-web-content nta-web-seach-element"
                                                            id="shopg_single_component_image">
                                                            <div class="nta-web-box-icon">
                                                                <i class="nta-web-icon anticon">
                                                                    <svg height="1em" width="1em"
                                                                        viewBox="0 0 488.471 488.471"
                                                                        fill="currentColor"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="m244.235 0c-134.669 0-244.235 109.566-244.235 244.235s109.566 244.235 244.235 244.235 244.235-109.566 244.235-244.235-109.566-244.235-244.235-244.235zm-213.706 244.235c0-117.839 95.867-213.706 213.706-213.706s213.706 95.867 213.706 213.706c0 25.435-4.699 49.733-12.889 72.379l-128.965-128.965c-5.963-5.963-15.622-5.963-21.585 0l-204.318 204.318c-36.854-38.415-59.654-90.418-59.655-147.732zm213.706 213.706c-49.541 0-95.078-17.096-131.362-45.494l192.421-192.421 126.504 126.504c-36.317 66.323-106.763 111.411-187.563 111.411z">
                                                                        </path>
                                                                        <path
                                                                            d="m152.647 244.235c33.675 0 61.059-27.384 61.059-61.059s-27.384-61.059-61.059-61.059-61.059 27.384-61.059 61.059 27.384 61.059 61.059 61.059zm0-91.588c16.83 0 30.529 13.699 30.529 30.529s-13.699 30.529-30.529 30.529-30.529-13.699-30.529-30.529 13.699-30.529 30.529-30.529z">
                                                                        </path>
                                                                    </svg>
                                                                </i>
                                                            </div>
                                                            <div class="nta-web-name-icon-element">
                                                                <p>Logo</p>
                                                            </div>
                                                        </div>
                                                        <div class="component nta-web-content nta-web-seach-element"
                                                            id="shopg_single_component_heading">
                                                            <div class="nta-web-box-icon">
                                                                <i aria-label="icon: font-colors"
                                                                    class="nta-web-icon anticon anticon-font-colors">
                                                                    <svg viewBox="64 64 896 896" data-icon="font-colors"
                                                                        width="1em" height="1em" fill="currentColor"
                                                                        aria-hidden="true" focusable="false" class="">
                                                                        <path
                                                                            d="M904 816H120c-4.4 0-8 3.6-8 8v80c0 4.4 3.6 8 8 8h784c4.4 0 8-3.6 8-8v-80c0-4.4-3.6-8-8-8zm-650.3-80h85c4.2 0 8-2.7 9.3-6.8l53.7-166h219.2l53.2 166c1.3 4 5 6.8 9.3 6.8h89.1c1.1 0 2.2-.2 3.2-.5a9.7 9.7 0 0 0 6-12.4L573.6 118.6a9.9 9.9 0 0 0-9.2-6.6H462.1c-4.2 0-7.9 2.6-9.2 6.6L244.5 723.1c-.4 1-.5 2.1-.5 3.2-.1 5.3 4.3 9.7 9.7 9.7zm255.9-516.1h4.1l83.8 263.8H424.9l84.7-263.8z">
                                                                        </path>
                                                                    </svg>
                                                                </i>
                                                            </div>
                                                            <div class="nta-web-name-icon-element">
                                                                <p>Heading</p>
                                                            </div>
                                                        </div>


                                                        <div id="hidden-component-html" style="display: none;">
                                                            <div id="component-html-heading">
                                                                <div class="added-component" id="">
                                                                    <div class="wrap-custom-heading">
                                                                        <h1>Custom Heading</h1>
                                                                        <p>This is General Description</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="component-html-image">
                                                                <div class="added-component" id=""><img
                                                                        src="<?php echo SHOPGLUT_URL . 'assets/images/woocommerce-logo.png'; ?>"
                                                                        alt="Custom Image" /></div>
                                                            </div>
                                                        </div>

                                                    </div>


                                                </div>

                                            </div>
                                            <!---->

                                        </div>
                                    </div>
                                    <div class="__rail-is-vertical"
                                        style="position: absolute; z-index: 1; border-radius: 6px; background: rgba(1, 169, 154, 0); border: none; width: 6px; top: 0px; bottom: 0px; right: 2px;">
                                        <div class="__bar-wrap-is-vertical"
                                            style="position: absolute; border-radius: 6px; top: 0px; bottom: 0px; width: 100%;">
                                            <div class="__bar-is-vertical"
                                                style="cursor: pointer; position: absolute; margin: auto; transition: opacity 0.5s ease 0s; user-select: none; border-radius: inherit; height: 52.4515%; background: rgb(85, 88, 89); width: 3px; opacity: 0; transform: translateY(0%); left: 0px; right: 0px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php do_action('shopglut_single_settings', 'shopglut');?>

                                <div class="shopg-editor-back">
                                    <div class="shopg-editor-back-title">


                                        <i class="fa-solid fa-arrow-left"></i>
                                        <h5 class="nta-web-name-control">Back To Wordpress</h5>



                                    </div>
                                </div>
                                <div class="shopg-back-all-single">
                                    <div class="nta-web-back-wordpress-wrap">
                                        <div class="yaymail-back-wp">
                                            <a class="nta-web-back-wordpress-link"
                                                href="http://shopglut.local/wp-admin/"><span
                                                    class="nta-web-back-wordpress-title">BACK TO DASHBOARD</span></a>
                                            <a class="nta-web-back-wordpress-icon"
                                                href="http://shopglut.local/wp-admin/">

                                            </a>
                                        </div>

                                    </div>
                                </div>

                            </div>


                        </div>
                    </aside>
                    <section class="nta-web-content-build yaymail-layout" id="nta-web-content-build"
                        style="background-color: rgb(236, 236, 236);">
                        <div>
                            <div class="nta-web-header">
                                <span data-v-6301aa5c="">
                                    <div data-v-6301aa5c=""
                                        class="nta-web-email-type yaymail-select yaymail-select-enabled">
                                        <div role="combobox" aria-autocomplete="list" aria-haspopup="true"
                                            aria-controls="2735e1ad-bdfd-4faf-c758-5119150b1c82"
                                            class="yaymail-select-selection yaymail-select-selection--single">
                                            <div class="yaymail-select-selection__rendered">
                                                <div title="" class="yaymail-select-selection-selected-value"
                                                    style="display: block; opacity: 1;"><span data-v-6301aa5c=""
                                                        class="nta-web-header-dropdown-status"><span data-v-6301aa5c=""
                                                            class="nta-web-header-dropdown-status-name">New order</span>
                                                        <span data-v-6301aa5c=""
                                                            class="nta-web-header-dropdown-status-circle"
                                                            style="background-color: rgb(82, 196, 26);"></span></span>
                                                </div>
                                            </div>
                                            <span unselectable="on" class="yaymail-select-arrow"
                                                style="user-select: none;">
                                                <i aria-label="icon: down"
                                                    class="anticon anticon-down yaymail-select-arrow-icon">
                                                    <svg viewBox="64 64 896 896" data-icon="down" width="1em"
                                                        height="1em" fill="currentColor" aria-hidden="true"
                                                        focusable="false" class="">
                                                        <path
                                                            d="M884 256h-75c-5.1 0-9.9 2.5-12.9 6.6L512 654.2 227.9 262.6c-3-4.1-7.8-6.6-12.9-6.6h-75c-6.5 0-10.3 7.4-6.5 12.7l352.6 486.1c12.8 17.6 39 17.6 51.7 0l352.6-486.1c3.9-5.3.1-12.7-6.4-12.7z">
                                                        </path>
                                                    </svg>
                                                </i>
                                            </span>
                                        </div>
                                    </div>
                                    <!---->
                                </span>

                                <div class="nta-web-header-control">
                                    <button type="button" class="nta-web-bt-shortcodes yaymail-btn"
                                        ant-click-animating-without-extra-node="false">
                                        <i aria-label="icon: info" class="anticon anticon-info">
                                            <svg viewBox="64 64 896 896" data-icon="info" width="1em" height="1em"
                                                fill="currentColor" aria-hidden="true" focusable="false" class="">
                                                <path
                                                    d="M448 224a64 64 0 1 0 128 0 64 64 0 1 0-128 0zm96 168h-64c-4.4 0-8 3.6-8 8v464c0 4.4 3.6 8 8 8h64c4.4 0 8-3.6 8-8V400c0-4.4-3.6-8-8-8z">
                                                </path>
                                            </svg>
                                        </i>
                                    </button>
                                    <span data-v-0520e311="" class="nta-web-bt-send"
                                        style="display: inline-block; cursor: not-allowed;">
                                        <button data-v-0520e311="" disabled="disabled" type="button"
                                            class="nta-web-bt-send yaymail-btn" style="pointer-events: none;">
                                            <i data-v-0520e311="" aria-label="icon: mail" class="anticon anticon-mail">
                                                <svg viewBox="64 64 896 896" data-icon="mail" width="1em" height="1em"
                                                    fill="currentColor" aria-hidden="true" focusable="false" class="">
                                                    <path
                                                        d="M928 160H96c-17.7 0-32 14.3-32 32v640c0 17.7 14.3 32 32 32h832c17.7 0 32-14.3 32-32V192c0-17.7-14.3-32-32-32zm-40 110.8V792H136V270.8l-27.6-21.5 39.3-50.5 42.8 33.3h643.1l42.8-33.3 39.3 50.5-27.7 21.5zM833.6 232L512 482 190.4 232l-42.8-33.3-39.3 50.5 27.6 21.5 341.6 265.6a55.99 55.99 0 0 0 68.7 0L888 270.8l27.6-21.5-39.3-50.5-42.7 33.2z">
                                                    </path>
                                                </svg>
                                            </i>
                                        </button>
                                    </span>
                                    <span class="">
                                        <button type="button" class="nta-web-bt-blank yaymail-btn">
                                            <i aria-label="icon: file" class="anticon anticon-file">
                                                <svg viewBox="64 64 896 896" data-icon="file" width="1em" height="1em"
                                                    fill="currentColor" aria-hidden="true" focusable="false" class="">
                                                    <path
                                                        d="M854.6 288.6L639.4 73.4c-6-6-14.1-9.4-22.6-9.4H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V311.3c0-8.5-3.4-16.7-9.4-22.7zM790.2 326H602V137.8L790.2 326zm1.8 562H232V136h302v216a42 42 0 0 0 42 42h216v494z">
                                                    </path>
                                                </svg>
                                            </i>
                                        </button>
                                        <!---->
                                    </span>
                                    <button type="button" class="nta-web-bt-copy-template yaymail-btn">
                                        <i aria-label="icon: copy" class="anticon anticon-copy">
                                            <svg viewBox="64 64 896 896" data-icon="copy" width="1em" height="1em"
                                                fill="currentColor" aria-hidden="true" focusable="false" class="">
                                                <path
                                                    d="M832 64H296c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h496v688c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8V96c0-17.7-14.3-32-32-32zM704 192H192c-17.7 0-32 14.3-32 32v530.7c0 8.5 3.4 16.6 9.4 22.6l173.3 173.3c2.2 2.2 4.7 4 7.4 5.5v1.9h4.2c3.5 1.3 7.2 2 11 2H704c17.7 0 32-14.3 32-32V224c0-17.7-14.3-32-32-32zM350 856.2L263.9 770H350v86.2zM664 888H414V746c0-22.1-17.9-40-40-40H232V264h432v624z">
                                                </path>
                                            </svg>
                                        </i>
                                    </button>
                                    <span data-v-603e4c92="" class="">
                                        <button data-v-603e4c92="" type="button"
                                            class="nta-web-bt-reset-tem yaymail-btn">
                                            <i data-v-603e4c92="" aria-label="icon: rollback"
                                                class="anticon anticon-rollback">
                                                <svg viewBox="64 64 896 896" data-icon="rollback" width="1em"
                                                    height="1em" fill="currentColor" aria-hidden="true"
                                                    focusable="false" class="">
                                                    <path
                                                        d="M793 242H366v-74c0-6.7-7.7-10.4-12.9-6.3l-142 112a8 8 0 0 0 0 12.6l142 112c5.2 4.1 12.9.4 12.9-6.3v-74h415v470H175c-4.4 0-8 3.6-8 8v60c0 4.4 3.6 8 8 8h618c35.3 0 64-28.7 64-64V306c0-35.3-28.7-64-64-64z">
                                                    </path>
                                                </svg>
                                            </i>
                                        </button>
                                        <!---->
                                    </span>
                                    <span class="" style="display: inline-block; cursor: not-allowed;">
                                        <button disabled="disabled" type="button" class="yaymail-btn"
                                            style="pointer-events: none;">
                                            <i aria-label="icon: undo" class="anticon anticon-undo">
                                                <svg viewBox="64 64 896 896" data-icon="undo" width="1em" height="1em"
                                                    fill="currentColor" aria-hidden="true" focusable="false" class="">
                                                    <path
                                                        d="M511.4 124C290.5 124.3 112 303 112 523.9c0 128 60.2 242 153.8 315.2l-37.5 48c-4.1 5.3-.3 13 6.3 12.9l167-.8c5.2 0 9-4.9 7.7-9.9L369.8 727a8 8 0 0 0-14.1-3L315 776.1c-10.2-8-20-16.7-29.3-26a318.64 318.64 0 0 1-68.6-101.7C200.4 609 192 567.1 192 523.9s8.4-85.1 25.1-124.5c16.1-38.1 39.2-72.3 68.6-101.7 29.4-29.4 63.6-52.5 101.7-68.6C426.9 212.4 468.8 204 512 204s85.1 8.4 124.5 25.1c38.1 16.1 72.3 39.2 101.7 68.6 29.4 29.4 52.5 63.6 68.6 101.7 16.7 39.4 25.1 81.3 25.1 124.5s-8.4 85.1-25.1 124.5a318.64 318.64 0 0 1-68.6 101.7c-7.5 7.5-15.3 14.5-23.4 21.2a7.93 7.93 0 0 0-1.2 11.1l39.4 50.5c2.8 3.5 7.9 4.1 11.4 1.3C854.5 760.8 912 649.1 912 523.9c0-221.1-179.4-400.2-400.6-399.9z">
                                                    </path>
                                                </svg>
                                            </i>
                                        </button>
                                    </span>
                                    <span class="" style="display: inline-block; cursor: not-allowed;">
                                        <button disabled="disabled" type="button" class="yaymail-btn"
                                            style="pointer-events: none;">
                                            <i aria-label="icon: redo" class="anticon anticon-redo">
                                                <svg viewBox="64 64 896 896" data-icon="redo" width="1em" height="1em"
                                                    fill="currentColor" aria-hidden="true" focusable="false" class="">
                                                    <path
                                                        d="M758.2 839.1C851.8 765.9 912 651.9 912 523.9 912 303 733.5 124.3 512.6 124 291.4 123.7 112 302.8 112 523.9c0 125.2 57.5 236.9 147.6 310.2 3.5 2.8 8.6 2.2 11.4-1.3l39.4-50.5c2.7-3.4 2.1-8.3-1.2-11.1-8.1-6.6-15.9-13.7-23.4-21.2a318.64 318.64 0 0 1-68.6-101.7C200.4 609 192 567.1 192 523.9s8.4-85.1 25.1-124.5c16.1-38.1 39.2-72.3 68.6-101.7 29.4-29.4 63.6-52.5 101.7-68.6C426.9 212.4 468.8 204 512 204s85.1 8.4 124.5 25.1c38.1 16.1 72.3 39.2 101.7 68.6 29.4 29.4 52.5 63.6 68.6 101.7 16.7 39.4 25.1 81.3 25.1 124.5s-8.4 85.1-25.1 124.5a318.64 318.64 0 0 1-68.6 101.7c-9.3 9.3-19.1 18-29.3 26L668.2 724a8 8 0 0 0-14.1 3l-39.6 162.2c-1.2 5 2.6 9.9 7.7 9.9l167 .8c6.7 0 10.5-7.7 6.3-12.9l-37.3-47.9z">
                                                    </path>
                                                </svg>
                                            </i>
                                        </button>
                                    </span>
                                </div>
                                <div class="nta-web-header-control-pre-sav">
                                    <button type="button" class="nta-web-bt-preview yaymail-btn"
                                        ant-click-animating-without-extra-node="false">
                                        <i aria-label="icon: eye" class="anticon anticon-eye">
                                            <svg viewBox="64 64 896 896" data-icon="eye" width="1em" height="1em"
                                                fill="currentColor" aria-hidden="true" focusable="false" class="">
                                                <path
                                                    d="M942.2 486.2C847.4 286.5 704.1 186 512 186c-192.2 0-335.4 100.5-430.2 300.3a60.3 60.3 0 0 0 0 51.5C176.6 737.5 319.9 838 512 838c192.2 0 335.4-100.5 430.2-300.3 7.7-16.2 7.7-35 0-51.5zM512 766c-161.3 0-279.4-81.8-362.7-254C232.6 339.8 350.7 258 512 258c161.3 0 279.4 81.8 362.7 254C791.5 684.2 673.4 766 512 766zm-4-430c-97.2 0-176 78.8-176 176s78.8 176 176 176 176-78.8 176-176-78.8-176-176-176zm0 288c-61.9 0-112-50.1-112-112s50.1-112 112-112 112 50.1 112 112-50.1 112-112 112z">
                                                </path>
                                            </svg>
                                        </i>
                                        <span class="nta-web-bt-preview-text">Preview </span>
                                    </button>
                                    <div data-v-5daf4926="" style="display: inline-block;">
                                        <button data-v-5daf4926="" type="button" id="save-now"
                                            class="nta-web-bt-save yaymail-btn">
                                            <i data-v-5daf4926="" aria-label="icon: save" class="anticon anticon-save">
                                                <svg viewBox="64 64 896 896" data-icon="save" width="1em" height="1em"
                                                    fill="currentColor" aria-hidden="true" focusable="false" class="">
                                                    <path
                                                        d="M893.3 293.3L730.7 130.7c-7.5-7.5-16.7-13-26.7-16V112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V338.5c0-17-6.7-33.2-18.7-45.2zM384 184h256v104H384V184zm456 656H184V184h136v136c0 17.7 14.3 32 32 32h320c17.7 0 32-14.3 32-32V205.8l136 136V840zM512 442c-79.5 0-144 64.5-144 144s64.5 144 144 144 144-64.5 144-144-64.5-144-144-144zm0 224c-44.2 0-80-35.8-80-80s35.8-80 80-80 80 35.8 80 80-35.8 80-80 80z">
                                                    </path>
                                                </svg>
                                            </i>
                                            <span>Save</span>
                                        </button>
                                        <div id="message" class="message">It's OK</div>

                                        <!---->
                                    </div>
                                </div>
                            </div>


                            <div id="right-sidebar-editor"></div>
                            <div id="settings-panel"></div>


                            <div class="nta-web-panel-switcher">
                                <!---->
                                <p class="nta-web-panel-switcher-wrap">
                                    <i aria-label="icon: caret-left" class="anticon anticon-caret-left">
                                        <svg viewBox="0 0 1024 1024" data-icon="caret-left" width="1em" height="1em"
                                            fill="currentColor" aria-hidden="true" focusable="false" class="">
                                            <path
                                                d="M689 165.1L308.2 493.5c-10.9 9.4-10.9 27.5 0 37L689 858.9c14.2 12.2 35 1.2 35-18.5V183.6c0-19.7-20.8-30.7-35-18.5z">
                                            </path>
                                        </svg>
                                    </i>
                                </p>
                            </div>
                        </div>
                    </section>

                </section>
            </div>
        </div>
    </section>
</div>
<style>
.shopg-editor-heading {
    background-color: #7f54b3;
}

.added-component {

    margin-top: 2%;
}

.shopglut-single-component-settings {
    overflow: hidden scroll;
    flex: 1;
    top: 77px;
    bottom: 50px;
    width: 100%;
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
}

.shopg-editor-heading {
    display: flex;

}

.shopg-back-all-single {

    padding: 20px;
}

.shopg-editor-heading .shopg-editor-heading-title {
    margin-right: 40px;
    padding: 12px;
}

.shopg-editor-heading .icon {
    padding: 12px;
}

.shopg-editor-back-title {
    display: flex;
    margin-left: 25px;
    padding: 3px;
    padding-top: 18px;

}

.shopg-editor-back-title i {
    margin-top: 3px;
}

.yaymail-layout-sider-children {
    height: 100%;
    margin-top: -.1px;
    padding-top: .1px;
}

.nta-web-setting {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.shopglut-single-component-settings .agl-content {
    background-color: transparent;
}

.shopg_single_component_heading,
.shopg_single_component_image {
    display: none;
}

.components-settings-head {
    display: none;
    padding-top: 15px;
    height: 50px;
    text-align: center;
    border-bottom: 1px solid hsla(0, 0%, 100%, .03);
    cursor: pointer;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}

.shopglut-single-component-settings .agl-field .agl-title h4 {
    color: #fff !important;
}

.components-settings-data {
    cursor: pointer;
    color: #ccc;
    margin: -3px 0 0 35px;
    padding: 0;
    text-align: left;
    font-size: 15px;
    font-weight: 600;
    color: #fff;
}

.components-settings-data i {
    color: #878787;
    padding-right: 4px;
}

.shopglut-admin-sg-single-product #adminmenuwrap,
.shopglut-admin-sg-single-product #adminmenuback,
.shopglut-admin-sg-single-product #wpadminbar,
.shopglut-admin-sg-single-product #wpfooter {
    display: none;
}

html.wp-toolbar {
    padding-top: 0px;
}

.shopglut-admin-sg-single-product #wpcontent,
.shopglut-admin-sg-single-product #wpbody-content {
    margin-left: 0px;
    padding-bottom: 0px;
}

body.shopglut-admin-sg-single-product #screen-meta-links {
    display: none;
}

.wp-picker-open+.wp-picker-input-wrap {
    display: inline-flex !important;
}

#message {
    position: fixed;
    bottom: -50px;
    /* Initially hidden below the view */
    right: 20px;
    background-color: #4CAF50;
    /* Green background */
    color: white;
    padding: 16px;
    z-index: 1000;
    border-radius: 4px;
    display: none;
    /* Initially hidden */
}

@keyframes slideIn {
    from {
        bottom: -50px;
        opacity: 0;
    }

    to {
        bottom: 20px;
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        bottom: 20px;
        opacity: 1;
    }

    to {
        bottom: -50px;
        opacity: 0;
    }
}

.slide-in {
    animation: slideIn 0.5s forwards;
}

.slide-out {
    animation: slideOut 0.5s forwards;
}

/*! CSS Used from: http://shopglut.local/wp-admin/load-styles.php?c=1&dir=ltr&load%5Bchunk_0%5D=dashicons,admin-bar,common,forms,admin-menu,dashboard,list-tables,edit,revisions,media,themes,about,nav-menus,wp-pointer,widgets&load%5Bchunk_1%5D=,site-icon,l10n,buttons,wp-auth-check,media-views,wp-color-picker&ver=6.5.5 ; media=all */
@media all {
    img {
        border: 0;
    }

    td {
        font-family: inherit;
        font-size: inherit;
        font-weight: inherit;
        line-height: inherit;
    }

    a {
        color: #2271b1;
        transition-property: border, background, color;
        transition-duration: .05s;
        transition-timing-function: ease-in-out;
    }

    a,
    div {
        outline: 0;
    }

    a:active,
    a:hover {
        color: #135e96;
    }

    p {
        font-size: 13px;
        line-height: 1.5;
        margin: 1em 0;
    }

    h1,
    h2,
    h5 {
        display: block;
        font-weight: 600;
    }

    h1 {
        color: #1d2327;
        font-size: 2em;
        margin: .67em 0;
    }

    h2 {
        color: #1d2327;
        font-size: 1.3em;
        margin: 1em 0;
    }

    h5 {
        font-size: .83em;
        margin: 1.67em 0;
    }

    img {
        border: none;
    }

    @media screen and (max-width:782px) {
        body * {
            -webkit-tap-highlight-color: transparent !important;
        }
    }

    button,
    input {
        box-sizing: border-box;
        font-family: inherit;
        font-size: inherit;
        font-weight: inherit;
    }

    input {
        font-size: 14px;
    }

    input {
        margin: 0 1px;
    }

    input[type=text] {
        box-shadow: 0 0 0 transparent;
        border-radius: 4px;
        border: 1px solid #8c8f94;
        background-color: #fff;
        color: #2c3338;
    }

    input[type=text] {
        padding: 0 8px;
        line-height: 2;
        min-height: 30px;
    }

    input[type=text]:focus {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        outline: 2px solid transparent;
    }

    input:disabled {
        background: rgba(255, 255, 255, .5);
        border-color: rgba(220, 220, 222, .75);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .04);
        color: rgba(44, 51, 56, .5);
    }

    @media screen and (max-width:782px) {
        input[type=text] {
            -webkit-appearance: none;
            padding: 3px 10px;
            min-height: 40px;
        }

        input {
            font-size: 16px;
        }
    }
}

/*! CSS Used from: http://shopglut.local/wp-content/plugins/yaymail/assets/dist/css/main.css?ver=3.5.2 ; media=all */
@media all {

    *,
    :after,
    :before {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    aside,
    main,
    section {
        display: block;
    }

    [tabindex="-1"]:focus {
        outline: none !important;
    }

    h1,
    h2,
    h5 {
        margin-top: 0;
        margin-bottom: .5em;
        color: rgba(0, 0, 0, .85);
        font-weight: 500;
    }

    p {
        margin-top: 0;
        margin-bottom: 1em;
    }

    address {
        margin-bottom: 1em;
        font-style: normal;
        line-height: inherit;
    }

    input[type=text] {
        -webkit-appearance: none;
    }

    a {
        color: #1890ff;
        text-decoration: none;
        background-color: transparent;
        outline: none;
        cursor: pointer;
        -webkit-transition: color .3s;
        transition: color .3s;
        -webkit-text-decoration-skip: objects;
    }

    a:hover {
        color: #40a9ff;
    }

    a:active {
        color: #096dd9;
    }

    a:active,
    a:hover {
        text-decoration: none;
        outline: 0;
    }

    img {
        vertical-align: middle;
        border-style: none;
    }

    svg:not(:root) {
        overflow: hidden;
    }

    a,
    button,
    input:not([type=range]) {
        -ms-touch-action: manipulation;
        touch-action: manipulation;
    }

    table {
        border-collapse: collapse;
    }

    th {
        text-align: inherit;
    }

    button,
    input {
        margin: 0;
        color: inherit;
        font-size: inherit;
        font-family: inherit;
        line-height: inherit;
    }

    button,
    input {
        overflow: visible;
    }

    button {
        text-transform: none;
    }

    button,
    html [type=button] {
        -webkit-appearance: button;
    }

    ::selection {
        color: #fff;
        background: #1890ff;
    }

    .anticon {
        display: inline-block;
        color: inherit;
        font-style: normal;
        line-height: 0;
        text-align: center;
        text-transform: none;
        vertical-align: -.125em;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .anticon>* {
        line-height: 1;
    }

    .anticon svg {
        display: inline-block;
    }

    .anticon:before {
        display: none;
    }

    .anticon[tabindex] {
        cursor: pointer;
    }

    .yaymail-spin-nested-loading {
        position: relative;
    }

    .yaymail-spin-container {
        position: relative;
        -webkit-transition: opacity .3s;
        transition: opacity .3s;
    }

    .yaymail-spin-container:after {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 10;
        display: none\9;
        width: 100%;
        height: 100%;
        background: #fff;
        opacity: 0;
        -webkit-transition: all .3s;
        transition: all .3s;
        content: "";
        pointer-events: none;
    }

    .yaymail-select {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        color: rgba(0, 0, 0, .65);
        font-size: 14px;
        font-variant: tabular-nums;
        line-height: 1.5;
        -webkit-font-feature-settings: "tnum";
        font-feature-settings: "tnum";
        position: relative;
        display: inline-block;
        outline: 0;
    }

    .yaymail-select {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .yaymail-select-arrow {
        display: inline-block;
        color: inherit;
        font-style: normal;
        line-height: 0;
        text-align: center;
        text-transform: none;
        vertical-align: -.125em;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        position: absolute;
        top: 50%;
        right: 11px;
        margin-top: -6px;
        color: rgba(0, 0, 0, .25);
        font-size: 12px;
        line-height: 1;
        -webkit-transform-origin: 50% 50%;
        -ms-transform-origin: 50% 50%;
        transform-origin: 50% 50%;
    }

    .yaymail-select-arrow>* {
        line-height: 1;
    }

    .yaymail-select-arrow svg {
        display: inline-block;
    }

    .yaymail-select-arrow:before {
        display: none;
    }

    .yaymail-select-arrow .yaymail-select-arrow-icon {
        display: block;
    }

    .yaymail-select-arrow .yaymail-select-arrow-icon svg {
        -webkit-transition: -webkit-transform .3s;
        transition: -webkit-transform .3s;
        transition: transform .3s;
        transition: transform .3s, -webkit-transform .3s;
    }

    .yaymail-select-selection {
        display: block;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        background-color: #fff;
        border: 1px solid #d9d9d9;
        border-top: 1.02px solid #d9d9d9;
        border-radius: 4px;
        outline: none;
        -webkit-transition: all .3s cubic-bezier(.645, .045, .355, 1);
        transition: all .3s cubic-bezier(.645, .045, .355, 1);
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .yaymail-select-selection:hover {
        border-color: #40a9ff;
        border-right-width: 1px !important;
    }

    .yaymail-select-selection:active,
    .yaymail-select-selection:focus {
        border-color: #40a9ff;
        border-right-width: 1px !important;
        outline: 0;
        -webkit-box-shadow: 0 0 0 2px rgba(24, 144, 255, .2);
        box-shadow: 0 0 0 2px rgba(24, 144, 255, .2);
    }

    .yaymail-select-selection-selected-value {
        float: left;
        max-width: 100%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .yaymail-select-selection--single {
        position: relative;
        height: 32px;
        cursor: pointer;
    }

    .yaymail-select-selection--single .yaymail-select-selection__rendered {
        margin-right: 24px;
    }

    .yaymail-select-selection__rendered {
        position: relative;
        display: block;
        margin-right: 11px;
        margin-left: 11px;
        line-height: 30px;
    }

    .yaymail-select-selection__rendered:after {
        display: inline-block;
        width: 0;
        visibility: hidden;
        content: ".";
        pointer-events: none;
    }

    .yaymail-row {
        display: block;
    }

    .yaymail-row {
        position: relative;
        height: auto;
        margin-right: 0;
        margin-left: 0;
        zoom: 1;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .yaymail-row:after,
    .yaymail-row:before {
        display: table;
        content: "";
    }

    .yaymail-row:after {
        clear: both;
    }

    .yaymail-row-flex {
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-flow: row wrap;
        flex-flow: row wrap;
    }

    .yaymail-row-flex,
    .yaymail-row-flex:after,
    .yaymail-row-flex:before {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .yaymail-row-flex-center {
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
    }

    .yaymail-row-flex-space-around {
        -ms-flex-pack: distribute;
        justify-content: space-around;
    }

    .yaymail-row-flex-middle {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .yaymail-col {
        position: relative;
        min-height: 1px;
    }

    .yaymail-col-2,
    .yaymail-col-12,
    .yaymail-col-20,
    .yaymail-col-22,
    .yaymail-col-24 {
        position: relative;
        padding-right: 0;
        padding-left: 0;
    }

    .yaymail-col-2,
    .yaymail-col-12,
    .yaymail-col-20,
    .yaymail-col-22,
    .yaymail-col-24 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        float: left;
    }

    .yaymail-col-24 {
        display: block;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        width: 100%;
    }

    .yaymail-col-22 {
        display: block;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        width: 91.66666667%;
    }

    .yaymail-col-20 {
        display: block;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        width: 83.33333333%;
    }

    .yaymail-col-12 {
        display: block;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        width: 50%;
    }

    .yaymail-col-2 {
        display: block;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        width: 8.33333333%;
    }

    .yaymail-btn {
        line-height: 1.499;
        position: relative;
        display: inline-block;
        font-weight: 400;
        white-space: nowrap;
        text-align: center;
        background-image: none;
        -webkit-box-shadow: 0 2px 0 rgba(0, 0, 0, .015);
        box-shadow: 0 2px 0 rgba(0, 0, 0, .015);
        cursor: pointer;
        -webkit-transition: all .3s cubic-bezier(.645, .045, .355, 1);
        transition: all .3s cubic-bezier(.645, .045, .355, 1);
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        height: 32px;
        padding: 0 15px;
        font-size: 14px;
        border-radius: 4px;
        color: rgba(0, 0, 0, .65);
        background-color: #fff;
        border: 1px solid #d9d9d9;
    }

    .yaymail-btn>.anticon {
        line-height: 1;
    }

    .yaymail-btn,
    .yaymail-btn:active,
    .yaymail-btn:focus {
        outline: 0;
    }

    .yaymail-btn:not([disabled]):hover {
        text-decoration: none;
    }

    .yaymail-btn:not([disabled]):active {
        outline: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .yaymail-btn[disabled] {
        cursor: not-allowed;
    }

    .yaymail-btn[disabled]>* {
        pointer-events: none;
    }

    .yaymail-btn:focus,
    .yaymail-btn:hover {
        color: #40a9ff;
        background-color: #fff;
        border-color: #40a9ff;
    }

    .yaymail-btn:active {
        color: #096dd9;
        background-color: #fff;
        border-color: #096dd9;
    }

    .yaymail-btn[disabled],
    .yaymail-btn[disabled]:active,
    .yaymail-btn[disabled]:focus,
    .yaymail-btn[disabled]:hover {
        color: rgba(0, 0, 0, .25);
        background-color: #f5f5f5;
        border-color: #d9d9d9;
        text-shadow: none;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .yaymail-btn:active,
    .yaymail-btn:focus,
    .yaymail-btn:hover {
        text-decoration: none;
        background: #fff;
    }

    .yaymail-btn>i,
    .yaymail-btn>span {
        display: inline-block;
        -webkit-transition: margin-left .3s cubic-bezier(.645, .045, .355, 1);
        transition: margin-left .3s cubic-bezier(.645, .045, .355, 1);
        pointer-events: none;
    }

    .yaymail-btn-primary {
        color: #fff;
        background-color: #1890ff;
        border-color: #1890ff;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, .12);
        -webkit-box-shadow: 0 2px 0 rgba(0, 0, 0, .045);
        box-shadow: 0 2px 0 rgba(0, 0, 0, .045);
    }

    .yaymail-btn-primary:focus,
    .yaymail-btn-primary:hover {
        color: #fff;
        background-color: #40a9ff;
        border-color: #40a9ff;
    }

    .yaymail-btn-primary:active {
        color: #fff;
        background-color: #096dd9;
        border-color: #096dd9;
    }

    .yaymail-btn:before {
        position: absolute;
        top: -1px;
        right: -1px;
        bottom: -1px;
        left: -1px;
        z-index: 1;
        display: none;
        background: #fff;
        border-radius: inherit;
        opacity: .35;
        -webkit-transition: opacity .2s;
        transition: opacity .2s;
        content: "";
        pointer-events: none;
    }

    .yaymail-btn .anticon {
        -webkit-transition: margin-left .3s cubic-bezier(.645, .045, .355, 1);
        transition: margin-left .3s cubic-bezier(.645, .045, .355, 1);
    }

    .yaymail-btn:active>span,
    .yaymail-btn:focus>span {
        position: relative;
    }

    .yaymail-btn>.anticon+span {
        margin-left: 8px;
    }

    .yaymail-btn:empty {
        vertical-align: top;
    }

    .yaymail-switch {
        margin: 0;
        padding: 0;
        color: rgba(0, 0, 0, .65);
        font-size: 14px;
        font-variant: tabular-nums;
        line-height: 1.5;
        list-style: none;
        -webkit-font-feature-settings: "tnum";
        font-feature-settings: "tnum";
        position: relative;
        display: inline-block;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        min-width: 44px;
        height: 22px;
        line-height: 20px;
        vertical-align: middle;
        background-color: rgba(0, 0, 0, .25);
        border: 1px solid transparent;
        border-radius: 100px;
        cursor: pointer;
        -webkit-transition: all .36s;
        transition: all .36s;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .yaymail-switch-inner {
        display: block;
        margin-right: 6px;
        margin-left: 24px;
        color: #fff;
        font-size: 12px;
    }

    .yaymail-switch:after {
        position: absolute;
        top: 1px;
        left: 1px;
        width: 18px;
        height: 18px;
        background-color: #fff;
        border-radius: 18px;
        cursor: pointer;
        -webkit-transition: all .36s cubic-bezier(.78, .14, .15, .86);
        transition: all .36s cubic-bezier(.78, .14, .15, .86);
        content: " ";
    }

    .yaymail-switch:after {
        -webkit-box-shadow: 0 2px 4px 0 rgba(0, 35, 11, .2);
        box-shadow: 0 2px 4px 0 rgba(0, 35, 11, .2);
    }

    .yaymail-switch:not(.yaymail-switch-disabled):active:after,
    .yaymail-switch:not(.yaymail-switch-disabled):active:before {
        width: 24px;
    }

    .yaymail-switch:focus {
        outline: 0;
        -webkit-box-shadow: 0 0 0 2px rgba(24, 144, 255, .2);
        box-shadow: 0 0 0 2px rgba(24, 144, 255, .2);
    }

    .yaymail-switch:focus:hover {
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .yaymail-switch-checked {
        background-color: #1890ff;
    }

    .yaymail-switch-checked .yaymail-switch-inner {
        margin-right: 24px;
        margin-left: 6px;
    }

    .yaymail-switch-checked:after {
        left: 100%;
        margin-left: -1px;
        -webkit-transform: translateX(-100%);
        -ms-transform: translateX(-100%);
        transform: translateX(-100%);
    }

    .yaymail-input {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        margin: 0;
        font-variant: tabular-nums;
        list-style: none;
        -webkit-font-feature-settings: "tnum";
        font-feature-settings: "tnum";
        position: relative;
        display: inline-block;
        width: 100%;
        height: 32px;
        padding: 4px 11px;
        color: rgba(0, 0, 0, .65);
        font-size: 14px;
        line-height: 1.5;
        background-color: #fff;
        background-image: none;
        border: 1px solid #d9d9d9;
        border-radius: 4px;
        -webkit-transition: all .3s;
        transition: all .3s;
    }

    .yaymail-input:focus,
    .yaymail-input:hover {
        border-color: #40a9ff;
        border-right-width: 1px !important;
    }

    .yaymail-input:focus {
        outline: 0;
        -webkit-box-shadow: 0 0 0 2px rgba(24, 144, 255, .2);
        box-shadow: 0 0 0 2px rgba(24, 144, 255, .2);
    }

    .yaymail-input-affix-wrapper {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        color: rgba(0, 0, 0, .65);
        font-size: 14px;
        font-variant: tabular-nums;
        line-height: 1.5;
        list-style: none;
        -webkit-font-feature-settings: "tnum";
        font-feature-settings: "tnum";
        position: relative;
        display: inline-block;
        width: 100%;
        text-align: start;
    }

    .yaymail-input-affix-wrapper:hover .yaymail-input:not(.yaymail-input-disabled) {
        border-color: #40a9ff;
        border-right-width: 1px !important;
    }

    .yaymail-input-affix-wrapper .yaymail-input {
        position: relative;
        text-align: inherit;
    }

    .yaymail-input-affix-wrapper .yaymail-input-suffix {
        position: absolute;
        top: 50%;
        z-index: 2;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        color: rgba(0, 0, 0, .65);
        line-height: 0;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    .yaymail-input-affix-wrapper .yaymail-input-suffix :not(.anticon) {
        line-height: 1.5;
    }

    .yaymail-input-affix-wrapper .yaymail-input-suffix {
        right: 12px;
    }

    .yaymail-input-affix-wrapper .yaymail-input:not(:last-child) {
        padding-right: 30px;
    }

    .yaymail-input-search-icon {
        color: rgba(0, 0, 0, .45);
        cursor: pointer;
        -webkit-transition: all .3s;
        transition: all .3s;
    }

    .yaymail-input-search-icon:hover {
        color: rgba(0, 0, 0, .8);
    }

    .yaymail-layout {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 1;
        -ms-flex: auto;
        flex: auto;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        min-height: 0;
        background: #f0f2f5;
    }

    .yaymail-layout.yaymail-layout-has-sider {
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row;
    }

    .yaymail-layout.yaymail-layout-has-sider>.yaymail-layout {
        overflow-x: hidden;
    }

    .yaymail-layout-content {
        -webkit-box-flex: 1;
        -ms-flex: auto;
        flex: auto;
        min-height: 0;
    }

    .yaymail-layout-sider {
        position: relative;
        min-width: 0;
        background: #001529;
        -webkit-transition: all .2s;
        transition: all .2s;
    }

    #nta-web-page-builder {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        height: 100vh;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        width: 100%;
    }

    #nta-web-page-builder .anticon {
        vertical-align: -.125em;
    }

    #nta-web-page-builder .anticon:before {
        content: unset !important;
    }

    .billing-shipping-address-container>span {
        border-color: inherit;
    }

    .yaymail_builder_link,
    .yaymail_builder_order {
        color: inherit;
    }

    #nta-web-build-email-content {
        padding: 0;
        min-height: 200px;
        width: 100%;
        height: auto;
    }

    .nta-web-block-mover {
        position: absolute;
        left: -41px;
        top: 2px;
        display: none;
        z-index: 6;
        width: 35px;
        height: 80px;
        border-radius: 3px;
        box-shadow: 0 0 1px 0 rgba(0, 0, 0, .25);
        -webkit-box-shadow: 0 0 1px 0 rgba(0, 0, 0, .25);
        -moz-box-shadow: 0 0 1px 0 rgba(0, 0, 0, .25);
        background-color: #fff;
    }

    .nta-web-block-mover:hover {
        box-shadow: 0 2px 10px rgba(25, 30, 35, .1);
        -webkit-box-shadow: 0 2px 10px rgba(25, 30, 35, .1);
        -moz-box-shadow: 0 2px 10px rgba(25, 30, 35, .1);
    }

    .nta-web-block-option {
        position: absolute;
        right: 4px;
        top: 2px;
        display: none;
        z-index: 3;
    }

    .web-dropdown-link {
        display: inline-block;
        width: 25px;
        height: 25px;
        border-radius: 7px;
        background-color: #f2f2f2;
        margin-top: 16px;
        -webkit-box-shadow: none;
        box-shadow: none;
        margin-right: 10px;
    }

    .web-dropdown-link:hover {
        background: #efe7ee;
    }

    .web-dropdown-link:hover .nta-icon-ellipsis {
        fill: #7f54b3;
    }

    .nta-icon-ellipsis {
        fill: #74788d;
    }

    .web-dropdown-link i {
        font-size: 16px;
        color: #986290;
        margin-top: 3px;
        margin-left: 4px;
        display: block;
    }

    .nta-web-icon-ellipsis {
        font-size: 16px;
        color: #7f54b3;
        margin-top: 2px;
        margin-left: 2px;
    }

    .nta-web-icon {
        font-size: 18px;
        cursor: pointer;
    }

    .nta-web-icon:hover {
        color: #333;
    }

    .nta-web-drag {
        top: 31px;
        right: 4px;
        padding: 0 5px;
        color: #74788d;
    }

    .nta-web-caret-up {
        top: 7px;
        right: 4px;
        padding: 0 5px;
        color: #74788d;
    }

    .nta-web-caret-down {
        top: 56px;
        right: 4px;
        padding: 0 5px;
        color: #74788d;
    }

    .nta-web-builder-element {
        z-index: 1;
        cursor: pointer;
        position: relative;
    }

    .nta-web-builder-element:hover {
        z-index: 2;
    }

    .nta-web-wrap {
        margin: 0 auto;
        width: 100%;
        min-height: 200px;
    }

    .nta-web-seach {
        margin-top: 11px;
        -webkit-box-shadow: none;
        box-shadow: none;
        outline: none;
    }

    .nta-web-panel-elements-categories {
        margin-top: 10px;
    }

    .nta-web-panel-elements {
        display: none;
        margin-top: 10px;
    }

    .nta-web-panel-elements .nta-web-container-element {
        border-radius: 7px 7px 7px 7px;
    }

    .nta-web-title-wrap {
        padding-left: 15px;
        font-size: 11px;
        color: #fff;
        cursor: pointer;
        background: #26292b;
        height: 44px;
        line-height: 45px;
        position: relative;
        border-radius: 7px 7px 7px 7px;
        width: 92%;
        margin: 0 auto;
        padding-right: 15px;
    }

    .nta-web-title-wrap.nta-web-panel-el-action {
        background: #2d3032;
        border-radius: 7px 7px 0 0;
    }

    .nta-web-title-wrap.nta-web-panel-el-action .nta-web-title {
        color: #fff;
        border-bottom: 1px solid hsla(0, 0%, 100%, .03);
    }

    .nta-web-title {
        color: #e6e8eb;
        line-height: 45px;
        height: 44px;
        margin: 0;
        font-size: 14px;
        font-weight: 600;
    }

    .nta-web-icon-down,
    .nta-web-icon-right {
        position: absolute;
        top: 18px;
        right: 15px;
        color: hsla(0, 0%, 100%, .5);
        font-size: 11px;
    }

    .nta-web-container-element {
        overflow: hidden;
        width: 92%;
        margin: 0 auto;
        background: #2d3032;
        padding: 15px 10px 5px 5px;
        border-radius: 0 0 7px 7px;
    }

    .nta-web-container-element-generalEl,
    .nta-web-container-element-wooEl {
        display: none;
    }

    .nta-web-content.nta-web-seach-element {
        color: #fff;
        background: #383b3c;
        text-align: center;
        border-radius: 5px;
        width: 45%;
        display: inline-block;
        float: left;
    }

    .nta-web-content:nth-child(odd) {
        margin: 7px 14px 7px 9px;
    }

    .nta-web-content:nth-child(2n) {
        margin: 7px 0;
    }

    .nta-web-content .nta-web-name-icon-element {
        padding: 0 7px;
    }

    .nta-web-content:first-child,
    .nta-web-content:nth-child(2) {
        margin-top: 0 !important;
    }

    .nta-web-content-search {
        margin: 7px 5px 7px 8px !important;
    }

    .nta-web-panel-elements .nta-web-container-element {
        padding-top: 7px !important;
    }

    .nta-web-content-search.nta-web-content:first-child,
    .nta-web-content-search.nta-web-content:nth-child(2) {
        margin-top: 7px !important;
    }

    .nta-web-content:hover {
        cursor: pointer;
        box-shadow: 3px 4px 4px rgba(35, 40, 45, .4588235294);
        -webkit-box-shadow: 3px 4px 4px rgba(35, 40, 45, .4588235294);
        -moz-box-shadow: 3px 4px 4px rgba(35, 40, 45, .4588235294);
    }

    .nta-web-box-icon {
        height: 40px;
        line-height: 60px;
    }

    .nta-web-icon {
        font-size: 20px;
        color: #b9b9b9;
    }

    .nta-web-content:hover {
        background: #47494b;
    }

    .nta-web-content:hover .nta-web-icon,
    .nta-web-content:hover .nta-web-name-icon-element p {
        color: #fff;
    }

    .nta-web-name-icon-element {
        font-family: Roboto, sans-serif;
        height: 40px;
        line-height: 28px;
    }

    .nta-web-name-icon-element p {
        margin-bottom: 0;
        padding-top: 5px;
        font-size: 11px;
        color: #b9b9b9;
    }

    .nta-web-wrap-element .nta-web-title-general,
    .nta-web-wrap-element .nta-web-title-wooEl {
        margin-top: 10px;
    }

    .nta-web-back-wordpress .nta-web-back-wordpress-wrap {
        width: 100%;
        display: inline-block;
        top: 14px;
        position: relative;
    }

    .nta-web-back-wordpress .yaymail-back-wp {
        display: inline-block;
        position: relative;
        width: 65%;
        top: 10px;
    }

    .nta-web-back-wordpress .yaymail-btn-history {
        display: inline-block;
        margin-right: 12px;
        padding: 0;
    }

    .nta-web-back-wordpress .nta-web-icon-arrow-left {
        -webkit-transition: -webkit-transform .3s cubic-bezier(.02, .01, .47, 1);
        transition: -webkit-transform .3s cubic-bezier(.02, .01, .47, 1);
        transition: transform .3s cubic-bezier(.02, .01, .47, 1);
        transition: transform .3s cubic-bezier(.02, .01, .47, 1), -webkit-transform .3s cubic-bezier(.02, .01, .47, 1);
    }

    .nta-web-back-wordpress a:hover~a .nta-web-icon-arrow-left {
        -webkit-transform: translateX(-5px);
        -ms-transform: translateX(-5px);
        transform: translateX(-5px);
    }

    .nta-web-back-wordpress .nta-web-back-wordpress-icon:focus,
    .nta-web-back-wordpress .nta-web-back-wordpress-link:focus {
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .nta-web-siderbar {
        overflow: hidden;
        padding-bottom: 93px;
        max-width: 320px !important;
        min-width: 320px !important;
        background: #222527;
        float: left;
        height: 100vh;
    }

    .nta-web-heading {
        width: 100%;
        height: 52px;
        background: #7f54b3;
    }

    .nta-web-name-control {
        color: #fff;
        margin-bottom: 0;
        font-size: 13px;
        word-break: break-all;
        margin-left: 9px;
        font-weight: 500;
    }

    .nta-web-icon {
        font-size: 20px;
        color: #fff;
    }

    .nta-web-icon:hover {
        cursor: pointer;
        color: #d5dadf;
    }

    .shopglut-nav-content {
        background: #222527;
        height: 70px;
        display: -webkit-flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        border-bottom: 1px solid hsla(0, 0%, 100%, .03);
    }

    .nta-web-nav-content {
        margin-top: 15px;
        height: 50px;
        border-radius: 7px;
        background-color: hsla(0, 0%, 100%, .03);
        width: 92%;
        margin-left: 13px;
    }

    .nta-web-tab-control {
        text-align: center;
        line-height: 42px;
        height: 42px;
        margin-bottom: 0;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
    }

    .nta-web-tab-control:hover {
        cursor: pointer;
    }

    .activeTab {
        background: #484949;
        color: #7f54b3;
        height: 40px;
        border-radius: 5px;
        background-color: #e6e8eb;
        margin-left: 6px;
        margin-right: 6px;
    }

    .nta-web-nav-contaner {
        font-size: 14px;
        color: #ccc;
        height: auto;
        font-family: Roboto, Open Sans, sans-serif;
        padding-bottom: 20%;
        height: 100%;
    }

    .nta-web-nav-contaner .nta-web-arrow-left {
        position: absolute;
        left: 18px;
        color: #8e8f90;
        font-size: 17px;
        top: -2px;
        -webkit-transition: -webkit-transform .3s cubic-bezier(.02, .01, .47, 1);
        transition: -webkit-transform .3s cubic-bezier(.02, .01, .47, 1);
        transition: transform .3s cubic-bezier(.02, .01, .47, 1);
        transition: transform .3s cubic-bezier(.02, .01, .47, 1), -webkit-transform .3s cubic-bezier(.02, .01, .47, 1);
    }

    .nta-web-nav {
        height: 50px;
        text-align: center;
        border-bottom: 1px solid hsla(0, 0%, 100%, .03);
        cursor: pointer;
    }

    .nta-web-nav:hover .nta-web-arrow-left {
        -webkit-transform: translateX(-5px);
        -ms-transform: translateX(-5px);
        transform: translateX(-5px);
    }

    .nta-web-nav-contaner .nta-web-tab_control {
        cursor: pointer;
        color: #ccc;
        margin: -3px 0 0 44px;
        padding: 0;
        text-align: left;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
    }

    .nta-web-wrap-element {
        background: #222527;
    }

    .yaymail-btn-go-pro-wrap {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin-top: 20px;
    }

    .yaymail-btn-go-pro {
        color: #fff;
        width: auto !important;
    }

    .nta-web-email-type[data-v-6301aa5c] {
        width: 215px;
        display: none;
    }

    .nta-web-bt-save[data-v-5daf4926] {
        opacity: 1;
    }

    .nta-web-bt-shortcodes {
        color: rgba(0, 0, 0, .65);
    }

    .nta-web-bt-send[data-v-0520e311] {
        color: rgba(0, 0, 0, .65);
    }

    span.nta-web-bt-send button.nta-web-bt-send[data-v-0520e311] {
        margin-top: 0;
        margin-left: 0;
    }

    @media screen and (max-width:768px) {
        .nta-web-bt-preview-text[data-v-7516d936] {
            display: none;
        }
    }

    .nta-web-bt-blank {
        color: rgba(0, 0, 0, .65);
    }

    .nta-web-bt-copy-template {
        color: rgba(0, 0, 0, .65);
    }

    .nta-web-bt-reset-tem[data-v-603e4c92] {
        margin-left: 5px;
    }

    .nta-web-switch {
        margin-right: 12px;
        width: 32px;
        height: 24px;
        border-radius: 25px;
    }

    .nta-web-switch:after {
        width: 18px;
        height: 18px;
        background-color: #fff;
        margin-top: 1px;
    }

    .yaymail-switch-checked {
        background-color: #7f54b3;
    }

    .yaymail-switch-checked:after {
        background-color: #fff;
    }

    .nta-web-header {
        padding: 5px 0;
        line-height: normal;
        position: sticky;
        top: 0;
        z-index: 7;
        -webkit-box-shadow: 0 0 0 1px #e2e4e7;
        box-shadow: 0 0 0 1px #e2e4e7;
        background: #fff;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }

    .nta-web-header> {
        margin: 5px 0 5px 10px;
    }

    .nta-web-email-order {
        width: 200px;
        display: inline-block;
    }

    .nta-web-container {
        height: auto;
        width: auto;
        margin: 0 auto;
    }

    .nta-web-content {
        width: 100%;
    }

    .nta-web-header-control {
        display: -webkit-box;
        display: -ms-flexbox;
        display: none;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .nta-web-header-control>:not(:last-child) {
        margin-right: 5px;
    }

    .nta-web-header-control-pre-sav {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        margin-left: auto;
        margin-right: 10px;
    }

    .nta-web-header-control-pre-sav> {
        margin-left: 10px;
    }

    .nta-web-panel-switcher {
        position: absolute;
        top: 50%;
        z-index: 8;
        background: #363738;
        border-radius: 0 4px 4px 0;
        cursor: pointer;
        width: 17px;
    }

    .nta-web-panel-switcher:hover {
        background: #434546;
    }

    .nta-web-panel-switcher-wrap {
        padding-top: 11px;
        padding-left: 2px;
        margin-bottom: 16px !important;
    }

    .nta-web-panel-switcher-wrap i {
        color: #fff;
    }

    #nta-web-build-email-content h1 {
        font-size: 2em;
        font-weight: 700;
    }

    #nta-web-build-email-content h2 {
        font-size: 18px;
        font-weight: 700;
    }

    #nta-web-build-email-content p {
        margin: 0;
    }

    #nta-web-build-email-content address {
        font-style: italic;
    }

    #nta-web-build-email-content a {}

    .nta-web-content-build {
        height: 100vh;
        overflow: auto;
        padding-bottom: 72px;
    }

    #nta-web-build-email-content table.web-main-row a {
        pointer-events: none;
    }

    .nta-web-back-wordpress {
        position: absolute;
        bottom: 13px;
        width: 100%;
        padding-left: 15px;
    }

    .nta-web-back-wordpress span.nta-web-icon-arrow-left {
        display: inline-block;
    }

    .nta-web-back-wordpress span.nta-web-back-wordpress-title {
        font-family: Roboto, Open Sans, sans-serif;
        color: #7f54b3;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        line-height: normal;
        margin: 0;
        position: absolute;
        left: 25px;
        top: 2px;
        cursor: pointer;
    }

    .nta-web-back-wordpress i {
        font-size: 18px;
        color: #7f54b3;
    }

    .nta-web-header div.yaymail-select-selection {
        -webkit-box-shadow: none;
        box-shadow: none;
        border: 1px solid #e6e8eb;
        margin-right: 11px;
    }

    .nta-web-header div.yaymail-select-selection:active,
    .nta-web-header div.yaymail-select-selection:focus,
    .nta-web-header div.yaymail-select-selection:hover {
        border: 1px solid #7f54b3;
        box-shadow: 0 1px 5px 0 rgba(150, 95, 142, .15);
        -webkit-box-shadow: 0 1px 5px 0 rgba(150, 95, 142, .15);
        -moz-box-shadow: 0 1px 5px 0 rgba(150, 95, 142, .15);
    }

    .nta-web-header div.yaymail-select-selection .yaymail-select-selection-selected-value span {
        color: #333;
        font-size: 14px;
    }

    .nta-web-header .nta-web-bt-blank,
    .nta-web-header .nta-web-bt-copy-template,
    .nta-web-header .nta-web-bt-send,
    .nta-web-header .nta-web-bt-shortcodes {
        margin-left: 5px;
    }

    .nta-web-header button.yaymail-btn:active,
    .nta-web-header button.yaymail-btn:hover {
        border-color: #7f54b3;
    }

    .nta-web-header button.yaymail-btn:focus {
        border-color: #74788d;
    }

    .nta-web-header button.yaymail-btn svg {
        color: #74788d;
    }

    .nta-web-header button.yaymail-btn :active svg,
    .nta-web-header button.yaymail-btn:hover svg {
        color: #7f54b3;
    }

    .nta-web-header button.nta-web-bt-preview {
        background: #efefef;
        margin-right: 12px;
        border-color: #efefef;
    }

    .nta-web-header button.nta-web-bt-preview span {
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }

    .nta-web-header button.nta-web-bt-preview.yaymail-btn:active span,
    .nta-web-header button.nta-web-bt-preview.yaymail-btn:active svg,
    .nta-web-header button.nta-web-bt-preview.yaymail-btn:focus span,
    .nta-web-header button.nta-web-bt-preview.yaymail-btn:focus svg,
    .nta-web-header button.nta-web-bt-preview.yaymail-btn:hover span,
    .nta-web-header button.nta-web-bt-preview.yaymail-btn:hover svg,
    .nta-web-header button.nta-web-bt-preview svg {
        color: #333;
    }

    .nta-web-header button.nta-web-bt-preview.yaymail-btn:active,
    .nta-web-header button.nta-web-bt-preview.yaymail-btn:focus,
    .nta-web-header button.nta-web-bt-preview.yaymail-btn:hover {
        background: #f9f9f9;
        border-color: #f1f1f1;
    }

    .nta-web-header button.nta-web-bt-save.yaymail-btn {
        background: #7f54b3;
        border-color: #7f54b3;
    }

    .nta-web-header button.nta-web-bt-save.yaymail-btn svg {
        color: #fff;
    }

    .nta-web-header button.nta-web-bt-save.yaymail-btn span {
        font-size: 14px;
        font-weight: 500;
        color: #fff;
    }

    .nta-web-header button.nta-web-bt-save.yaymail-btn:active,
    .nta-web-header button.nta-web-bt-save.yaymail-btn:active svg,
    .nta-web-header button.nta-web-bt-save.yaymail-btn:focus,
    .nta-web-header button.nta-web-bt-save.yaymail-btn:focus svg,
    .nta-web-header button.nta-web-bt-save.yaymail-btn:hover,
    .nta-web-header button.nta-web-bt-save.yaymail-btn:hover svg {
        border-color: #3c2861;
        background: #3c2861;
        color: #fff;
    }

    .nta-web-nav-contaner .yaymail-btn-history,
    .nta-web-setting .yaymail-btn {
        background: #7f54b3;
        border: #bb77ae;
        float: right;
        height: 35px;
        margin-bottom: 5px;
        padding: 0 12px;
        font-family: Helvetica, Roboto, Arial, sans-serif;
    }

    .nta-web-nav-contaner .yaymail-btn-history:hover,
    .nta-web-setting .yaymail-btn:hover {
        background: #3c2861;
        border-color: #3c2861;
    }

    .nta-web-wrap-element .nta-web-seach input::placeholder {
        color: #404243 !important;
        font-size: 14px;
    }

    .nta-web-wrap-element .nta-web-seach input {
        background-color: rgba(0, 0, 0, .25);
        border: none;
        color: #b9b9b9;
        padding-left: 14px;
    }

    .nta-web-wrap-element .nta-web-seach input:focus {
        border: none !important;
        box-shadow: none !important;
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
        outline: none;
        background: #111213;
    }

    .nta-web-wrap-element .nta-web-seach i {
        color: hsla(0, 0%, 100%, .25);
        font-size: 15px;
    }

    .nta-web-title-wrap:hover {
        background: #313336;
    }

    div.yaymail-select-selection {
        -webkit-box-shadow: none;
        box-shadow: none;
        border: 1px solid #e6e8eb;
    }

    div.yaymail-select-selection:active,
    div.yaymail-select-selection:focus,
    div.yaymail-select-selection:hover {
        border: 1px solid #7f54b3;
        box-shadow: 0 1px 5px 0 rgba(150, 95, 142, .15);
        -webkit-box-shadow: 0 1px 5px 0 rgba(150, 95, 142, .15);
        -moz-box-shadow: 0 1px 5px 0 rgba(150, 95, 142, .15);
    }

    div.yaymail-select-selection .yaymail-select-selection-selected-value span {
        color: #b9b9b9;
        font-size: 14px;
    }

    .nta-web-spin-preview-email .yaymail-spin-container {
        height: 100%;
    }

    .nta-web-builder-element:hover table.web-main-row {
        box-shadow: 0 0 8px 0 rgba(0, 0, 0, .25);
        -webkit-box-shadow: 0 0 8px 0 rgba(0, 0, 0, .25);
        -moz-box-shadow: 0 0 8px 0 rgba(0, 0, 0, .25);
    }
}

/*! CSS Used from: http://shopglut.local/wp-content/plugins/yaymail/assets/admin/css/css.css?ver=3.5.2 ; media=all */
@media all {
    .yaymail-customizer-page-builder #wpbody-content p {
        margin-bottom: 0;
    }

    .yaymail-customizer-page-builder .anticon {
        vertical-align: -.125em;
    }

    .yaymail-customizer-page-builder .anticon::before {
        content: unset !important;
    }
}

/*! CSS Used from: Embedded */
table.yaymail_builder_table_items_content tbody tr th {
    vertical-align: middle;
    padding: 12px;
    text-align: left;
    font-size: 14px;
    border-width: 1px;
    border-style: solid;
    border-color: inherit;
}

table.yaymail_builder_table_items_content tbody,
table.yaymail_builder_table_items_content tbody tr,
span[data-shordcode="yaymail_billing_shipping_address_content"] tbody,
span[data-shordcode="yaymail_billing_shipping_address_content"] tbody tr {
    border-color: inherit;
}

table td {
    font-family: inherit;
}

.yaymail_builder_order {
    margin-bottom: 0.83em;
    display: block;
    font-family: inherit;
    font-size: 18px;
    font-weight: bold;
    line-height: 130%;
    margin: 0 0 18px;
}

table p {
    margin: 0px;
}

table h1,
table h2 {
    margin: 0px;
}

.td {
    color: inherit;
}

/*! CSS Used fontfaces */
</style>
<?php
}

	public static function get_instance() {
		static $instance;

		if (is_null($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}
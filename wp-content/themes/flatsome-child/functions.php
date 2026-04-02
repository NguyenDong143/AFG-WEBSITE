<?php
// Add custom Theme Functions here

function add_custom_script_to_footer() {
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Function to find element by text and add class to its container
        function addClassToContainer(searchText, className, containerSelector = '.row, .section, .footer-main') {
            var elements = document.querySelectorAll('div, p, span, h1, h2, h3, h4, h5, h6, a');
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].innerText && elements[i].innerText.toLowerCase().includes(searchText.toLowerCase())) {
                    var container = elements[i].closest(containerSelector);
                    if (container) {
                        container.classList.add(className);
                        // Stop after first match to avoid duplicates if possible, or remove break if multiple needed
                        break; 
                    }
                }
            }
        }

        // 1. Main Footer (Forward Together) -> .xc-footer
        addClassToContainer('Forward Together', 'xc-footer', '.section, .footer-main');

        // 2. See More Row -> .row-xem-them
        addClassToContainer('Xem Thêm Về Chúng Tôi', 'row-xem-them', '.row');
        addClassToContainer('See more about us', 'row-xem-them', '.row');

        // 3. Download App Row -> .row-tai-ung-dung
        addClassToContainer('Tải Ứng Dụng', 'row-tai-ung-dung', '.row');
        addClassToContainer('Download the App', 'row-tai-ung-dung', '.row');

        function buildModernAfgFooter() {
            var footer = document.querySelector('.xc-footer');
            if (!footer || footer.querySelector('.afg-footer-shell')) {
                return;
            }

            var contentRoot = footer.querySelector('.section-content') || footer.querySelector('.run-command') || footer;
            var mainRow = contentRoot.querySelector('.row');
            if (!mainRow) {
                return;
            }

            var cols = Array.from(mainRow.children).filter(function(node) {
                return node.classList && node.classList.contains('col');
            });
            if (!cols.length) {
                return;
            }

            var colContact = cols[0] || footer;
            var colLinks = cols[1] || footer;
            var colMap = cols[2] || footer;

            function escHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            function normText(value) {
                return String(value || '').replace(/\s+/g, ' ').trim();
            }

            function collectTexts(scopeSelector) {
                return Array.from((scopeSelector || footer).querySelectorAll('p, li, span'))
                    .map(function(node) {
                        return normText(node.textContent);
                    })
                    .filter(function(item) {
                        return item.length > 0;
                    });
            }

            var contactTexts = collectTexts(colContact);
            var companyName = contactTexts.find(function(text) {
                return /aver|afg/i.test(text);
            }) || 'Công ty cổ phần Aver Fulfillment Global';

            var address = contactTexts.find(function(text) {
                return /tầng|tòa|tố hữu|hà nội|nam từ liêm|địa chỉ/i.test(text);
            }) || 'Tầng 7 tòa nhà Viwaseen, 48 P. Tố Hữu, Trung Văn, Nam Từ Liêm, Hà Nội.';

            var supportText = contactTexts.find(function(text) {
                return /theo dõi|cập nhật|hỗ trợ|đồng hành|vận hành/i.test(text);
            }) || 'Đồng hành cùng doanh nghiệp TMĐT với dịch vụ fulfillment và logistics toàn diện.';

            var logoImg = colContact.querySelector('img') || footer.querySelector('img');
            var logoSrc = logoImg && logoImg.src ? logoImg.src : '';

            var badgeImg = Array.from(colContact.querySelectorAll('img')).find(function(img) {
                if (logoImg && img === logoImg) {
                    return false;
                }
                var src = (img.src || '').toLowerCase();
                var alt = (img.alt || '').toLowerCase();
                return /bocongthuong|registered|đã đăng ký|compliance|badge/.test(src + ' ' + alt);
            });

            var socialLinks = Array.from(colContact.querySelectorAll('a.icon, a[href*="facebook"], a[href*="linkedin"], a[href*="youtube"], a[href*="zalo"]'))
                .slice(0, 5)
                .map(function(link) {
                    return {
                        href: link.getAttribute('href') || '#',
                        html: (link.innerHTML || '').trim(),
                        label: normText(link.getAttribute('aria-label') || link.title || link.textContent) || 'Social'
                    };
                });

            if (!socialLinks.length) {
                socialLinks = [
                    { href: '#', html: 'f', label: 'Facebook' },
                    { href: '#', html: 'in', label: 'LinkedIn' },
                    { href: '#', html: 'yt', label: 'YouTube' }
                ];
            }

            var sourceLinks = Array.from(colLinks.querySelectorAll('a')).map(function(link) {
                return {
                    href: link.getAttribute('href') || '#',
                    text: normText(link.textContent)
                };
            });

            function pickLink(label, patterns, fallbackHref) {
                var matched = sourceLinks.find(function(item) {
                    return patterns.some(function(pattern) {
                        return pattern.test(item.text);
                    });
                });
                return {
                    label: label,
                    href: matched ? matched.href : fallbackHref
                };
            }

            var quickLinks = [
                pickLink('Dịch vụ AFG Fulfillment', [/dịch vụ/i, /service/i], '/dich-vu-afg-fulfillment/'),
                pickLink('Đội ngũ nhân sự', [/đội ngũ/i, /nhân sự/i], '/doi-ngu-nhan-su/'),
                pickLink('Tuyển dụng', [/tuyển dụng/i, /career/i], '/tuyen-dung/'),
                pickLink('Tin tức', [/tin tức/i, /blog/i, /news/i], '/tin-tuc/'),
                pickLink('Liên hệ', [/liên hệ/i, /contact/i], '/lien-he/')
            ];

            var mapIframe = colMap.querySelector('iframe[src*="google"]') || footer.querySelector('iframe[src*="google"]');
            var mapSrc = mapIframe && mapIframe.src ? mapIframe.src : 'https://www.google.com/maps?q=AFG%20Fulfillment%20Global%20Hanoi&output=embed';

            var telLink = footer.querySelector('a[href^="tel:"]');
            var hotline = telLink ? normText(telLink.textContent) : '038 565 8585';
            if (!hotline) {
                hotline = '038 565 8585';
            }

            var socialHtml = socialLinks.map(function(item) {
                return '<a class="afg-footer-social" href="' + escHtml(item.href) + '" aria-label="' + escHtml(item.label) + '" target="_blank" rel="noopener">' +
                    (item.html || '<span>' + escHtml(item.label.slice(0, 2)) + '</span>') +
                '</a>';
            }).join('');

            var linksHtml = quickLinks.map(function(item) {
                return '<li><a href="' + escHtml(item.href) + '"><span>' + escHtml(item.label) + '</span><i aria-hidden="true">→</i></a></li>';
            }).join('');

            var newFooterHtml = '' +
                '<div class="afg-footer-shell">' +
                    '<div class="afg-footer-grid">' +
                        '<section class="afg-footer-col afg-footer-col--contact">' +
                            (logoSrc ? '<div class="afg-footer-logo"><img src="' + escHtml(logoSrc) + '" alt="AFG Fulfillment Global"></div>' : '<div class="afg-footer-logo-text">AFG Fulfillment</div>') +
                            '<h3>Thông tin liên hệ</h3>' +
                            '<p class="afg-footer-company">' + escHtml(companyName) + '</p>' +
                            '<p class="afg-footer-address">' + escHtml(address) + '</p>' +
                            '<p class="afg-footer-support">' + escHtml(supportText) + '</p>' +
                            '<div class="afg-footer-socials">' + socialHtml + '</div>' +
                            (badgeImg && badgeImg.src ? '<div class="afg-footer-badge"><img src="' + escHtml(badgeImg.src) + '" alt="Đã đăng ký"></div>' : '') +
                        '</section>' +
                        '<nav class="afg-footer-col afg-footer-col--links" aria-label="Về AFG Fulfillment">' +
                            '<h3>Về AFG Fulfillment</h3>' +
                            '<ul>' + linksHtml + '</ul>' +
                        '</nav>' +
                        '<section class="afg-footer-col afg-footer-col--map">' +
                            '<h3>Bản đồ &amp; Địa chỉ</h3>' +
                            '<div class="afg-footer-map-wrap">' +
                                '<iframe src="' + escHtml(mapSrc) + '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen="" aria-label="Bản đồ AFG Fulfillment"></iframe>' +
                            '</div>' +
                        '</section>' +
                    '</div>' +
                    '<div class="afg-footer-bottom">' +
                        '<div class="afg-footer-bottom-row">' +
                            '<div class="afg-footer-bottom-brand">AFG Fulfillment Global</div>' +
                            '<div class="afg-footer-bottom-address">' + escHtml(address) + '</div>' +
                            '<div class="afg-footer-bottom-hotline">Hotline: ' + escHtml(hotline) + '</div>' +
                        '</div>' +
                        '<p class="afg-footer-copyright">© 2024 AFG Fulfillment Global. All rights reserved.</p>' +
                    '</div>' +
                '</div>';

            contentRoot.innerHTML = newFooterHtml;

            var copyrightSection = document.querySelector('.xc-footer-copyright');
            if (copyrightSection) {
                copyrightSection.style.display = 'none';
            }
        }

        buildModernAfgFooter();
        
        // 4. Ensure Header has expected class if needed (optional)
        // Check if header exists
        var header = document.querySelector('.header-main');
        if (header) {
            if (window.innerWidth >= 850) {
                header.classList.add('show-logo-center');
            } else {
                header.classList.remove('show-logo-center');
            }
            header.classList.add('xc-header-fixed');
        }

        function normalizeServiceItemsToTitleDesc(rowSelector) {
            var row = document.querySelector(rowSelector);
            if (!row) {
                return;
            }

            var paragraphs = row.querySelectorAll('.afg-service-list .text p');
            paragraphs.forEach(function(p) {
                if (p.querySelector('.afg-item-sub') && p.querySelector('strong')) {
                    return;
                }

                var raw = (p.textContent || '').replace(/\s+/g, ' ').trim();
                if (!raw) {
                    return;
                }

                var title = '';
                var desc = '';
                var splitIndex = -1;

                [' — ', ' - ', ': ', ', '].some(function(sep) {
                    var idx = raw.indexOf(sep);
                    if (idx > 0) {
                        splitIndex = idx;
                        title = raw.slice(0, idx).trim();
                        desc = raw.slice(idx + sep.length).trim();
                        return true;
                    }
                    return false;
                });

                if (splitIndex === -1) {
                    var words = raw.split(' ');
                    if (words.length > 6) {
                        title = words.slice(0, 6).join(' ');
                        desc = words.slice(6).join(' ');
                    } else {
                        title = raw;
                        desc = '';
                    }
                }

                p.innerHTML = '';

                var strong = document.createElement('strong');
                strong.textContent = title;
                p.appendChild(strong);

                if (desc) {
                    var sub = document.createElement('span');
                    sub.className = 'afg-item-sub';
                    sub.textContent = desc;
                    p.appendChild(sub);
                }
            });
        }

        normalizeServiceItemsToTitleDesc('.xc-home-tab .afg-service-row--logistics');
        normalizeServiceItemsToTitleDesc('.xc-home-tab .afg-service-row--expansion');

        function transformUspSection() {
            return;
            var uspRow = document.querySelector('.row-usp');
            if (!uspRow || uspRow.classList.contains('afg-usp-processed')) {
                return;
            }

            var cols = Array.from(uspRow.children).filter(function(node) {
                return node.classList && node.classList.contains('col');
            });
            if (cols.length < 2) {
                return;
            }

            var rightCol = cols[1];
            var rightInner = rightCol.querySelector('.col-inner') || rightCol;
            var headings = Array.from(rightInner.querySelectorAll('h3'));
            if (!headings.length) {
                return;
            }

            var items = headings.map(function(heading, idx) {
                var node = heading.nextElementSibling;
                var textParts = [];

                while (node && !/^H3$/.test(node.tagName)) {
                    if (node.tagName === 'STYLE' || node.tagName === 'SCRIPT') {
                        node = node.nextElementSibling;
                        continue;
                    }

                    if (node.tagName === 'UL' || node.tagName === 'OL') {
                        node.querySelectorAll('li').forEach(function(li) {
                            var t = (li.textContent || '').replace(/\s+/g, ' ').trim();
                            if (t) textParts.push(t);
                        });
                    } else {
                        var t = (node.textContent || '').replace(/\s+/g, ' ').trim();
                        if (t) textParts.push(t);
                    }
                    node = node.nextElementSibling;
                }

                var title = (heading.textContent || '').replace(/^\s*\d+\.\s*/, '').trim();
                var desc = textParts.join(' ')
                    .replace(/#text-\d+\s*\{[^}]*\}/gi, ' ')
                    .replace(/\s*\{\s*text-align:[^}]*\}/gi, ' ')
                    .replace(/\s+/g, ' ')
                    .trim();

                return {
                    title: title,
                    desc: desc,
                    index: String(idx + 1).padStart(2, '0')
                };
            }).filter(function(item) {
                if (!item.title) {
                    return false;
                }

                return !/điều gì khiến|what makes/i.test(item.title);
            });

            if (!items.length) {
                return;
            }

            var listHtml = '<div class="afg-usp-list">' + items.map(function(item) {
                return '<article class="afg-usp-item">' +
                    '<div class="afg-usp-icon">' + item.index + '</div>' +
                    '<div class="afg-usp-content">' +
                        '<h3 class="afg-usp-title">' + item.title + '</h3>' +
                        '<p class="afg-usp-desc">' + item.desc + '</p>' +
                    '</div>' +
                '</article>';
            }).join('') + '</div>';

            rightInner.innerHTML = listHtml;
            uspRow.classList.add('afg-usp-modern', 'afg-usp-processed');
        }

        transformUspSection();

    });
    </script>
    <?php
}
add_action('wp_footer', 'add_custom_script_to_footer');

/**
 * Add custom date element before blog post title.
 * This element is styled to be visible only within .xc-home-blog in style.css.
 */
function xc_add_date_before_blog_post_title() {
    echo '<div class="xc-post-date">' . get_the_date('d/m/y') . '</div>';
}
add_action('flatsome_blog_post_before', 'xc_add_date_before_blog_post_title');

// Add Custom Metabox for Press Banner
function grab_press_banner_add_meta_box() {
    add_meta_box(
        'grab_press_banner_meta',
        'Press Banner Settings',
        'grab_press_banner_meta_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'grab_press_banner_add_meta_box');

function grab_press_banner_meta_box_callback($post) {
    wp_nonce_field('grab_press_banner_save_meta_box_data', 'grab_press_banner_meta_box_nonce');

    $banner_text = get_post_meta($post->ID, '_grab_press_banner_text', true);
    $banner_image = get_post_meta($post->ID, '_grab_press_banner_image', true);
    
    // Default values if empty
    if(empty($banner_text)) $banner_text = ''; 

    ?>
    <p>
        <label for="grab_press_banner_text"><strong>Banner Title Text:</strong></label><br>
        <input type="text" id="grab_press_banner_text" name="grab_press_banner_text" value="<?php echo esc_attr($banner_text); ?>" style="width:100%;" placeholder="Default: Press Centre" />
    </p>

    <p>
        <label for="grab_press_banner_image"><strong>Banner Image URL:</strong></label><br>
        <input type="text" id="grab_press_banner_image" name="grab_press_banner_image" value="<?php echo esc_attr($banner_image); ?>" style="width:80%;" />
        <input type="button" class="button button-secondary" value="Upload Image" id="grab_press_upload_image_button" />
    </p>
    <p><em>Leave image blank to use default: /wp-content/uploads/heropress1.jpg</em></p>

    <script>
    jQuery(document).ready(function($){
        var mediaUploader;
        $('#grab_press_upload_image_button').click(function(e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Banner Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#grab_press_banner_image').val(attachment.url);
            });
            mediaUploader.open();
        });
    });
    </script>
    <?php
}

function grab_press_banner_save_meta_box_data($post_id) {
    if (!isset($_POST['grab_press_banner_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['grab_press_banner_meta_box_nonce'], 'grab_press_banner_save_meta_box_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['grab_press_banner_text'])) {
        update_post_meta($post_id, '_grab_press_banner_text', sanitize_text_field($_POST['grab_press_banner_text']));
    }
    if (isset($_POST['grab_press_banner_image'])) {
        update_post_meta($post_id, '_grab_press_banner_image', esc_url_raw($_POST['grab_press_banner_image']));
    }
}
add_action('save_post', 'grab_press_banner_save_meta_box_data');

// Custom Mobile Dropdown Menu (Grab Style)
function grab_custom_mobile_menu() {
    ?>
    <div id="grab-mobile-dropdown" style="display: none;">
        <?php
        if ( has_nav_menu( 'primary_mobile' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'primary_mobile',
                'container'      => false,
                'menu_class'     => 'grab-mobile-list',
                'depth'          => 2,
            ) );
        } elseif ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'grab-mobile-list',
                'depth'          => 2,
            ) );
        }
        ?>
    </div>

    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var mobileNavUl = document.querySelector('.mobile-nav');
        var dropdown = document.getElementById('grab-mobile-dropdown');
        
        // 1. Hide original icon via JS (and CSS below as backup)
        // We do not destroy it, just hide it so it can be enabled later if needed.
        var originalIcon = document.querySelector('.mobile-nav .nav-icon');
        if (originalIcon) originalIcon.style.display = 'none';

        if(mobileNavUl && dropdown) {
            // 2. Create NEW Custom Icon Element
            // We use the same classes 'nav-icon has-icon' to inherit theme styles, 
            // plus our own class to identify it.
            var newLi = document.createElement('li');
            newLi.className = 'nav-icon custom-grab-mobile-toggle has-icon';
            newLi.innerHTML = '<a href="#" class="is-small" role="button" aria-label="Menu" aria-expanded="false"><i class="icon-menu" aria-hidden="true"></i></a>';
            
            // 3. Insert it into the list (at the start)
            mobileNavUl.insertBefore(newLi, mobileNavUl.firstChild);

            // 4. Bind Click Event
            var toggleLink = newLi.querySelector('a');
            
            toggleLink.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                    dropdown.style.display = 'block';
                    newLi.classList.add('active');
                    toggleLink.setAttribute('aria-expanded', 'true');
                } else {
                    dropdown.style.display = 'none';
                    newLi.classList.remove('active');
                    toggleLink.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Close when clicking outside
            document.addEventListener('click', function(event) {
                var isClickInside = dropdown.contains(event.target) || newLi.contains(event.target);
                if (!isClickInside && dropdown.style.display === 'block') {
                    dropdown.style.display = 'none';
                    newLi.classList.remove('active');
                    toggleLink.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Submenu toggles (Accordion style)
            var subMenuParents = dropdown.querySelectorAll('.menu-item-has-children > a');
            subMenuParents.forEach(function(link) {
                link.addEventListener('click', function(ev) {
                    ev.preventDefault();
                    var sub = this.nextElementSibling;
                    if(sub && sub.classList.contains('sub-menu')) {
                         if(sub.style.display === 'block') {
                             sub.style.display = 'none';
                             this.classList.remove('open');
                         } else {
                             sub.style.display = 'block';
                             this.classList.add('open');
                         }
                    }
                });
            });
        }
    });
    </script>
    <style>
        /* Hide original icon via CSS to prevent flash */
        .mobile-nav .nav-icon:not(.custom-grab-mobile-toggle) {
            display: none !important;
        }

        /* Mobile Dropdown Styling */
        #grab-mobile-dropdown {
            position: fixed; 
            top: 60px; /* Adjust based on header height */
            left: 0;
            width: 100%;
            background: #fff;
            z-index: 9999;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            max-height: calc(100vh - 60px);
            overflow-y: auto;
            border-top: 1px solid #eee;
        }

        /* List Styling */
        ul.grab-mobile-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        ul.grab-mobile-list > li {
            border-bottom: 1px solid #f1f4f6;
            margin: 0;
        }
        ul.grab-mobile-list > li > a {
            display: block;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: 500;
            color: #363a45;
            text-decoration: none;
        }
        
        /* Submenu */
        ul.grab-mobile-list .sub-menu {
            display: none; 
            background: #f9f9f9;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        ul.grab-mobile-list li.menu-item-has-children > a::after {
            content: '+';
            font-family: monospace; 
            float: right;
            font-weight: bold;
            font-size: 18px;
            color: #00b14f;
        }
        ul.grab-mobile-list li.menu-item-has-children > a.open::after {
            content: '-';
        }
        
        ul.grab-mobile-list .sub-menu li a {
            padding: 12px 20px 12px 40px;
            font-size: 14px;
            color: #555;
            display: block;
            border-bottom: 1px solid #eee;
        }
    </style>
    <?php
}
// Use Flatsome native mobile/off-canvas menu for consistent responsive behavior.
// add_action('wp_footer', 'grab_custom_mobile_menu');
add_filter('wpcf7_autop_or_not', '__return_false');

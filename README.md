# GW Elements - Laviasana Widgets

Custom Elementor widgets for the Laviasana e-commerce website. This plugin provides a complete set of pixel-perfect widgets recreating the React/Tailwind design system.

## Requirements

- WordPress 6.4+
- PHP 8.2+
- Elementor 3.20+
- Elementor Pro 3.20+ (recommended)
- WooCommerce 8.0+ (for product widgets)

**Tested with:**
- Elementor 3.34.0
- Elementor Pro 3.34.0
- PHP 8.2 / 8.3
- WooCommerce 9.5

## Installation

1. Upload the `gw-elements` folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress plugins menu
3. Download Splide.js and place in `assets/vendor/splide/`:
   - [splide.min.js](https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js)
   - [splide.min.css](https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css)
4. Start using widgets in Elementor under the "GW Elements" category

## Widgets Included

### Layout & Navigation
- **GW Header** - Sticky header with logo, navigation, search, account & cart
- **GW Footer** - Multi-column footer with social links and copyright

### Hero Sections
- **GW Video Hero** - Full-screen video background with overlay and CTA
- **GW Image Hero** - Image background hero with text overlay
- **GW Page Header** - Simple page title section with subtitle

### Product Display (WooCommerce)
- **GW Product Slider** - Carousel of products (featured, new, sale, etc.)
- **GW Product Grid** - Grid layout with filtering
- **GW Product Card** - Single product card (can link to WC product or manual)
- **GW Category Slider** - Carousel of product categories
- **GW Category Card** - Single category card

### Content Sections
- **GW Storytelling** - Asymmetric grid of story cards
- **GW Story Card** - Single article/blog card
- **GW Feature Highlight** - Image + text split section with features list
- **GW Values Grid** - Icon-based value propositions grid
- **GW Trust Points** - Horizontal trust/USP bar
- **GW CTA Section** - Call-to-action block with buttons

### Services & Events
- **GW Service Card** - Service offering card with features list
- **GW Upcoming Events** - Grid of event cards with dates

### Forms & Engagement
- **GW Contact Form** - Contact form (native or shortcode)
- **GW Contact Info** - Contact information list with icons
- **GW Newsletter** - Email subscription form
- **GW FAQ Accordion** - Categorized FAQ accordion

## Design System

The plugin uses CSS custom properties matching the Laviasana design:

### Colors
```css
--gw-primary: 338 96% 22%;        /* Burgundy */
--gw-burgundy-light: 338 60% 94%; /* Light burgundy bg */
--gw-foreground: 0 0% 8%;         /* Near black */
--gw-muted-foreground: 0 0% 40%;  /* Gray text */
```

### Typography
- **Serif**: Playfair Display (headings)
- **Sans**: Outfit (body text)

### Breakpoints
- Mobile: 0-479px
- Tablet: 480-767px
- Desktop: 768-1023px
- Large: 1024px+

## Customization

All widgets support Elementor controls for:
- Colors and typography
- Spacing and sizing
- Responsive settings
- Content editing

### Adding Custom Icons

Edit `widgets/class-widget-base.php` and add icons to the `get_lucide_icons()` method:

```php
'your-icon' => '<path d="..."/>',
```

## Performance

- Assets load only when widgets are used on the page
- Splide carousel library loads conditionally
- Optimized CSS with minimal specificity
- Scripts defer-loaded for better LCP

## WooCommerce Integration

Product widgets automatically:
- Pull from WooCommerce products
- Use WC product images, prices, categories
- AJAX add-to-cart with cart count updates
- Support for variable/simple products

## Browser Support

- Chrome (last 2 versions)
- Firefox (last 2 versions)
- Safari (last 2 versions)
- Edge (last 2 versions)

## License

Proprietary - Laviasana / GoatrikWorks

## Author

Erik Elb (GoatrikWorks)
goatrikworks@gmail.com

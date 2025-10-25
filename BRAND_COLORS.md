# Brand Color Guide

## Primary Brand Color

**Hex**: `#f36c21` - Your signature orange color

## Tailwind CSS Color Classes

### Primary Colors (Your Brand Orange)

-   `primary-50` - `#fef7f0` (Lightest)
-   `primary-100` - `#feede0`
-   `primary-200` - `#fdd7c1`
-   `primary-300` - `#fcba97`
-   `primary-400` - `#fa936b`
-   `primary-500` - `#f36c21` ⭐ **Main Brand Color**
-   `primary-600` - `#e45a1a`
-   `primary-700` - `#c04815`
-   `primary-800` - `#9c3a16`
-   `primary-900` - `#7e3215`
-   `primary-950` - `#441708` (Darkest)

### Legacy Support

-   `brand-orange` - `#f36c21` (for backward compatibility)

### Updated Orange Palette

The default Tailwind orange colors have been replaced with your brand colors.

## Usage Examples

### Buttons

```html
<!-- Primary Button -->
<button class="bg-primary-500 hover:bg-primary-600 text-white">
    Primary Button
</button>

<!-- Gradient Button -->
<button
    class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white"
>
    Gradient Button
</button>
```

### Cards & Borders

```html
<!-- Card with Brand Border -->
<div class="border-l-4 border-primary-500 bg-white rounded-lg">Content</div>

<!-- Gradient Header -->
<div class="bg-gradient-to-r from-primary-600 to-primary-700">
    Header Content
</div>
```

### Form Elements

```html
<!-- Focus States -->
<input class="focus:ring-primary-500 focus:border-primary-500" />
<select class="focus:ring-primary-500 focus:border-primary-500"></select>
```

### Text Colors

```html
<!-- Brand Text -->
<span class="text-primary-600">Brand Text</span>
<span class="text-primary-700">Darker Brand Text</span>
```

## Implementation Status

✅ **Updated Components:**

-   Payment Management Pages
-   Tailwind Configuration
-   CSS Build Process

🔄 **Ready for Update:**

-   All other admin pages
-   Frontend pages
-   Forms and modals
-   Navigation elements

## Next Steps

1. Update remaining admin pages to use `primary-*` colors
2. Replace any remaining `blue-*` colors with `primary-*` equivalents
3. Update frontend pages with brand colors
4. Ensure consistent brand color usage across the entire application

## CSS Build Command

```bash
npm run build
```

Run this command after making any Tailwind configuration changes to rebuild the CSS with your brand colors.



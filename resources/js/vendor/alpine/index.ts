import Alpine from 'alpinejs';
import AsyncAlpine from 'async-alpine';
import AlpineLazyLoadAssets from "alpine-lazy-load-assets";
import mask from '@alpinejs/mask';
import slug from 'alpinejs-slug'
Alpine.plugin(AsyncAlpine)
Alpine.plugin(AlpineLazyLoadAssets);
Alpine.plugin(mask);
Alpine.plugin(slug);
Alpine.start();
window['Alpine'] = Alpine
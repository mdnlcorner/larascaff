@props(['variant' => 'primary', 'size' => 'md', 'type' => 'submit'])

<button type="{{ $type }}"  {{ $attributes->twMerge([
'inline-flex items-center font-semibold focus:outline-none justify-center gap-2 text-white transition-colors border rounded-lg disabled:cursor-not-allowed disabled:opacity-60',
$variant == 'primary' ? 'focus:bg-primary-600 dark:bg-primary bg-primary dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 ' : null,
$variant == 'success' ? 'focus:bg-success-600 dark:bg-success bg-success dark:hover:bg-success-600 hover:bg-success-600 dark:focus:text-success-200'  : null,
$variant == 'danger' ? 'focus:bg-danger-600 dark:bg-danger bg-danger dark:hover:bg-danger-600 hover:bg-danger-600 dark:focus:text-danger-200' : null,
$variant == 'warning' ? 'focus:bg-warning-600 dark:bg-warning bg-warning dark:hover:bg-warning-600 hover:bg-warning-600 dark:focus:text-warning-200' : null,
$variant == 'info' ? 'focus:bg-info-600 dark:bg-info bg-info dark:hover:bg-info-600 hover:bg-info-600 dark:focus:text-info-200' : null,
$variant == 'secondary' ? 'focus:bg-secondary-600 dark:bg-dark-800/70 bg-secondary-100 text-foreground dark:hover:bg-dark-800 hover:bg-secondary-200 dark:focus:text-secondary-200 ' : null,
$variant == 'dark' ? 'focus:bg-dark-600 dark:bg-dark-950 bg-dark dark:hover:bg-dark-600 hover:bg-dark-600 dark:focus:text-dark-200' : null,
// neon
$variant == 'neon-primary' ? 'shadow-lg border-0 dark:border shadow-primary focus:bg-primary-600 dark:bg-primary/30 bg-primary dark:text-primary-300 dark:border-primary dark:hover:text-primary-100 hover:bg-primary-600 dark:focus:text-primary-200 ' : null,
$variant == 'neon-success' ? 'shadow-lg border-0 dark:border shadow-success focus:bg-success-600 dark:bg-success/30 bg-success dark:text-success-300 dark:border-success dark:hover:text-success-100 hover:bg-success-600 dark:focus:text-success-200'  : null,
$variant == 'neon-danger' ? 'shadow-lg border-0 dark:border shadow-danger focus:bg-danger-600 dark:bg-danger/30 bg-danger dark:text-danger-300 dark:border-danger dark:hover:text-danger-100 hover:bg-danger-600 dark:focus:text-danger-200' : null,
$variant == 'neon-warning' ? 'shadow-lg border-0 dark:border shadow-warning focus:bg-warning-600 dark:bg-warning/30 bg-warning dark:text-warning-300 dark:border-warning dark:hover:text-warning-100 hover:bg-warning-600 dark:focus:text-warning-200' : null,
$variant == 'neon-info' ? 'shadow-lg border-0 dark:border shadow-info focus:bg-info-600 dark:bg-info/30 bg-info dark:text-info-300 dark:border-info dark:hover:text-info-100 hover:bg-info-600 dark:focus:text-info-200' : null,
$variant == 'neon-secondary' ? 'shadow-lg border-0 dark:border shadow-secondary focus:bg-secondary-600 dark:bg-secondary/30 bg-secondary dark:text-secondary-300 dark:border-secondary dark:hover:text-secondary-100 hover:bg-secondary-600 dark:focus:text-secondary-200 ' : null,
$variant == 'neon-dark' ? 'shadow-lg border-0 dark:border shadow-dark focus:bg-dark-600 dark:bg-dark-950 bg-dark dark:text-dark-300 dark:border-dark-950 dark:hover:text-dark-100 hover:bg-dark-600 dark:focus:text-dark-200' : null,
// outline
$variant == 'outline-primary' ? 'text-primary focus:text-primary-600 border-primary hover:text-primary-600 dark:hover:text-primary-600 dark:focus:text-primary-600' : null,
$variant == 'outline-success' ? 'text-success focus:text-success-600 border-success hover:text-success-600 dark:hover:text-success-600 dark:focus:text-success-600' : null,
$variant == 'outline-danger' ? 'text-danger focus:text-danger-600 border-danger hover:text-danger-600 dark:hover:text-danger-600 dark:focus:text-danger-600' : null,
$variant == 'outline-warning' ? 'text-warning focus:text-warning-600 border-warning hover:text-warning-600 dark:hover:text-warning-600 dark:focus:text-warning-600' : null,
$variant == 'outline-info' ? 'text-info focus:text-info-600 border-info hover:text-info-600 dark:hover:text-info-600 dark:focus:text-info-600' : null,
$variant == 'outline-secondary' ? 'text-secondary focus:text-secondary-600 border-secondary hover:text-secondary-600 dark:hover:text-secondary-600 dark:focus:text-secondary-600' : null,
$variant == 'outline-dark' ? 'text-dark focus:text-dark-600 border-dark-950 hover:text-dark-600 dark:hover:text-dark-600 dark:focus:text-dark-600' : null,
// size
$size == 'xs' ? 'px-2 text-xs leading-3 py-1.5': null,
$size == 'sm' ? 'px-3 py-1.5 text-sm': null,
$size == 'md' ? 'px-3 py-2 text-sm': null,
$size == 'lg' ? 'px-3 py-2.5 text-sm': null,
$size == 'xl' ? 'px-4 text-base py-3': null,
]) }} >
    {{ $slot }}
</button>
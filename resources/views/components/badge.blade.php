@props(['variant' => 'primary', 'size' => null])
<div {{ $attributes->twMerge([
    'inline-flex items-center justify-center gap-2 px-2 py-0.5 text-xs text-white transition-colors rounded-md disabled:cursor-not-allowed disabled:opacity-60',
    $variant == 'primary' ? 'focus:bg-primary-600 dark:bg-primary bg-primary dark:text-white dark:hover:bg-primary-600 hover:bg-primary-600 dark:focus:text-primary-200 ' : null,
    $variant == 'success' ? 'focus:bg-success-600 dark:bg-success bg-success dark:text-white dark:hover:bg-success-600 hover:bg-success-600 dark:focus:text-success-200'  : null,
    $variant == 'danger' ? 'focus:bg-danger-600 dark:bg-danger bg-danger dark:text-white dark:hover:bg-danger-600 hover:bg-danger-600 dark:focus:text-danger-200' : null,
    $variant == 'warning' ? 'focus:bg-warning-600 dark:bg-warning bg-warning dark:text-white dark:hover:bg-warning-600 hover:bg-warning-600 dark:focus:text-warning-200' : null,
    $variant == 'info' ? 'focus:bg-info-600 dark:bg-info bg-info dark:text-white dark:hover:bg-info-600 hover:bg-info-600 dark:focus:text-info-200' : null,
    $variant == 'secondary' ? 'focus:bg-secondary-600 dark:bg-secondary bg-secondary dark:text-white dark:hover:bg-secondary-600 hover:bg-secondary-600 dark:focus:text-secondary-200 ' : null,
    $variant == 'dark' ? 'focus:bg-dark-600 dark:bg-dark-950 bg-dark dark:text-white dark:hover:bg-dark-600 hover:bg-dark-600 dark:focus:text-dark-200' : null,
    
    $variant == 'neon-primary' ? 'dark:border-0 border border-primary dark:border focus:bg-primary-600 dark:bg-primary/30 bg-primary/20 text-primary dark:text-primary-300 dark:border-primary dark:hover:text-primary-100 hover:bg-primary/30 dark:focus:text-primary-200 ' : null,
    $variant == 'neon-success' ? 'dark:border-0 border border-success dark:border focus:bg-success-600 dark:bg-success/30 bg-success/20 text-success dark:text-success-300 dark:border-success dark:hover:text-success-100 hover:bg-success/30 dark:focus:text-success-200'  : null,
    $variant == 'neon-danger' ? 'dark:border-0 border border-danger dark:border focus:bg-danger-600 dark:bg-danger/30 bg-danger/20 text-danger dark:text-danger-300 dark:border-danger dark:hover:text-danger-100 hover:bg-danger/30 dark:focus:text-danger-200' : null,
    $variant == 'neon-warning' ? 'dark:border-0 border border-warning dark:border focus:bg-warning-600 dark:bg-warning/30 bg-warning/20 text-warning dark:text-warning-300 dark:border-warning dark:hover:text-warning-100 hover:bg-warning/30 dark:focus:text-warning-200' : null,
    $variant == 'neon-info' ? 'dark:border-0 border border-info dark:border focus:bg-info-600 dark:bg-info/30 bg-info/20 dark:text-info-300 text-info dark:border-info dark:hover:text-info-100 hover:bg-info/30 dark:focus:text-info-200' : null,
    $variant == 'neon-secondary' ? 'dark:border-0 border border-secondary dark:border focus:bg-secondary-600 dark:bg-secondary/30 bg-secondary/20 text-secondary dark:text-secondary-300 dark:border-secondary dark:hover:text-secondary-100 hover:bg-secondary/30 dark:focus:text-secondary-200 ' : null,
    $variant == 'neon-dark' ? 'dark:border-0 border border-dark dark:border focus:bg-dark-600 dark:bg-dark-950 bg-dark dark:text-dark-300 text-white dark:border-dark-950 dark:hover:text-dark-100 hover:bg-dark/30 dark:focus:text-dark-200' : null,
    // outline
    $variant == 'outline-primary' ? 'text-primary focus:text-primary-600 border border-primary hover:text-primary-600 dark:hover:text-primary-600 dark:focus:text-primary-600' : null,
    $variant == 'outline-success' ? 'text-success focus:text-success-600 border border-success hover:text-success-600 dark:hover:text-success-600 dark:focus:text-success-600' : null,
    $variant == 'outline-danger' ? 'text-danger focus:text-danger-600 border border-danger hover:text-danger-600 dark:hover:text-danger-600 dark:focus:text-danger-600' : null,
    $variant == 'outline-warning' ? 'text-warning focus:text-warning-600 border border-warning hover:text-warning-600 dark:hover:text-warning-600 dark:focus:text-warning-600' : null,
    $variant == 'outline-info' ? 'text-info focus:text-info-600 border border-info hover:text-info-600 dark:hover:text-info-600 dark:focus:text-info-600' : null,
    $variant == 'outline-secondary' ? 'text-secondary focus:text-secondary-600 border border-secondary hover:text-secondary-600 dark:hover:text-secondary-600 dark:focus:text-secondary-600' : null,
    $variant == 'outline-dark' ? 'text-dark focus:text-dark-600 border border-dark-950 hover:text-dark-600 dark:hover:text-dark-600 dark:focus:text-dark-600' : null,

]) }}>{{ $slot }}</div>
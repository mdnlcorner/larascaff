export declare type TOptionToast = {
    title?: string;
    body: string;
    type?: 'success' | 'error' | 'info' | 'warning' | 'default';
    closeable?: boolean;
    position?: 'top-right' | 'top-center' | 'top-left' | 'bottom-right' | 'bottom-center' | 'bottom-left';
};

export declare type TPositionToast<T> = {
    'top-right'?: T;
    'top-center'?: T;
    'top-left'?: T;
    'bottom-left'?: T;
    'bottom-right'?: T;
    'bottom-center'?: T;
};

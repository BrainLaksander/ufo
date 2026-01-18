import React, { createContext, useState } from "react";
export const NotificationContext = createContext();

export function NotificationProvider({ children }) {
    const [toasts, setToasts] = useState([]);

    const push = (message, type = "info") => {
        const id = Date.now();
        setToasts((t) => [...t, { id, message, type }]);
        setTimeout(() => {
            setToasts((t) => t.filter((x) => x.id !== id));
        }, 5000);
    };

    return (
        <NotificationContext.Provider value={{ toasts, push }}>
            {children}
            <div id="notification-root">
                {toasts.map((t) => (
                    <div key={t.id} className={`toast toast-${t.type}`}>
                        {t.message}
                    </div>
                ))}
            </div>
        </NotificationContext.Provider>
    );
}

/**
 * Enhanced StudentBurgerMenu dengan keyboard support
 *
 * Tambahan fitur:
 * - Esc key untuk close menu
 * - Keyboard navigation
 * - Focus management
 *
 * Jika ingin menambahkan ke StudentBurgerMenu:
 *
 * 1. Import useEffect
 * 2. Tambahkan useEffect di component
 * 3. Return cleanup function
 */

import { useEffect } from "react";

/**
 * Custom Hook untuk keyboard navigation
 *
 * Usage:
 * useKeyboardNavigation(open, onClose);
 */
export function useKeyboardNavigation(open, onClose) {
    useEffect(() => {
        const handleKeyDown = (event) => {
            // Esc key untuk close menu
            if (event.key === "Escape" && open) {
                onClose();
            }
        };

        // Tambah listener hanya jika menu open
        if (open) {
            document.addEventListener("keydown", handleKeyDown);
        }

        return () => {
            document.removeEventListener("keydown", handleKeyDown);
        };
    }, [open, onClose]);
}

/**
 * Custom Hook untuk manage focus
 * Useful untuk accessibility
 */
export function useFocusManagement(open, sidebarRef) {
    useEffect(() => {
        if (open && sidebarRef?.current) {
            // Focus first menu item
            const firstLink = sidebarRef.current.querySelector(
                ".student-burger-link",
            );
            if (firstLink) {
                firstLink.focus();
            }
        }
    }, [open, sidebarRef]);
}

/**
 * Helper function untuk menu state management
 */
export function createMenuState() {
    return {
        open: false,
        activeItem: null,
    };
}

/**
 * Action types untuk menu reducer
 */
export const MENU_ACTIONS = {
    OPEN: "OPEN",
    CLOSE: "CLOSE",
    TOGGLE: "TOGGLE",
    SET_ACTIVE: "SET_ACTIVE",
};

/**
 * Menu reducer untuk complex state management
 *
 * Usage:
 * const [state, dispatch] = useReducer(menuReducer, initialState);
 */
export function menuReducer(state, action) {
    switch (action.type) {
        case MENU_ACTIONS.OPEN:
            return { ...state, open: true };
        case MENU_ACTIONS.CLOSE:
            return { ...state, open: false };
        case MENU_ACTIONS.TOGGLE:
            return { ...state, open: !state.open };
        case MENU_ACTIONS.SET_ACTIVE:
            return { ...state, activeItem: action.payload };
        default:
            return state;
    }
}

/**
 * Utility untuk close menu on outside click
 */
export function useOutsideClick(ref, callback) {
    useEffect(() => {
        function handleClickOutside(event) {
            if (ref.current && !ref.current.contains(event.target)) {
                callback();
            }
        }

        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [ref, callback]);
}

// src/hooks.server.ts
import type { Handle } from '@sveltejs/kit';
// import { redirect } from '@sveltejs/kit'; // <-- Matikan import ini dulu

export const handle: Handle = async ({ event, resolve }) => {
    
    // --- DIMATIKAN SEMENTARA AGAR BISA CEK UI FRONTEND ---
    // const sessionId = event.cookies.get('session_id'); 
    // const isPublicRoute = event.url.pathname.startsWith('/login');

    // if (!sessionId && !isPublicRoute) {
    //     throw redirect(303, '/login');
    // }

    // if (sessionId && isPublicRoute) {
    //     throw redirect(303, '/dashboard');
    // }
    // -----------------------------------------------------

    const response = await resolve(event);
    return response;
};
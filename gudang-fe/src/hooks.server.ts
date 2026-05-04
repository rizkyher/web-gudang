import { verifyToken } from '$lib/server/jwt';
import { getPrisma } from '$lib/server/prisma';
import type { Handle } from '@sveltejs/kit';

export const handle: Handle = async ({ event, resolve }) => {
	// Inisialisasi Prisma dengan D1 Binding (jika ada di Cloudflare)
	const db = getPrisma(event.platform?.env);
	event.locals.db = db;

	const token = event.cookies.get('token');

	if (token) {
		const decoded = verifyToken(token) as any;
		if (decoded && decoded.id) {
			const user = await db.users.findUnique({
				where: { id: decoded.id },
				select: { id: true, name: true, email: true, role: true }
			});
			if (user) {
				event.locals.user = user;
			}
		}
	}

	return resolve(event);
};
// See https://svelte.dev/docs/kit/types#app.d.ts
// for information about these interfaces
declare global {
	namespace App {
		// interface Error {}
		interface Locals {
			user?: {
				id: number;
				name: string;
				email: string;
				role: string;
			};
			db: import('@prisma/client').PrismaClient;
		}
		interface Platform {
			env: {
				DB: import('@cloudflare/workers-types').D1Database;
			};
			context: {
				waitUntil(promise: Promise<any>): void;
			};
			caches: import('@cloudflare/workers-types').CacheStorage & { default: import('@cloudflare/workers-types').Cache };
		}
}

export {};

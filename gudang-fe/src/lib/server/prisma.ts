import { PrismaClient } from '@prisma/client';
import { PrismaD1 } from '@prisma/adapter-d1';

import { PrismaLibSql } from '@prisma/adapter-libsql';

// Handle BigInt serialization for JSON
(BigInt.prototype as any).toJSON = function () {
	return Number(this);
};

let prismaClient: PrismaClient;

export function getPrisma(env?: any) {
	if (prismaClient) return prismaClient;

	if (env && env.DB) {
		const adapter = new PrismaD1(env.DB);
		prismaClient = new PrismaClient({ adapter });
	} else {
		// Fallback untuk local development (bun dev)
		const adapter = new PrismaLibSql({ url: 'file:./dev.db' });
		prismaClient = new PrismaClient({ adapter });
	}

	return prismaClient;
}

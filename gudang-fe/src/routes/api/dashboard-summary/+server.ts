import { json } from '@sveltejs/kit';
import type { RequestHandler } from './$types';

export const GET: RequestHandler = async ({ locals }) => {
	if (!locals.user) {
		return json({ message: 'Unauthorized' }, { status: 401 });
	}

	const totalItems = await locals.db.items.count();
	
	const pendingRequestsCount = await locals.db.inventory_requests.count({
		where: { status: 'pending' }
	});
	
	const completedDeliveryOrders = await locals.db.delivery_orders.count({
		where: { status: 'delivered' }
	});

	const totalUsers = await locals.db.users.count();

	return json({
		totalItems,
		pendingRequests: pendingRequestsCount,
		completedDeliveryOrders,
		totalUsers
	});
};

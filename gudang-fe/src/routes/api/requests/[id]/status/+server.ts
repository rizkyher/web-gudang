import { json } from '@sveltejs/kit';
import type { RequestHandler } from './$types';
import { RequestStatusSchema } from '$lib/server/schemas';

export const PUT: RequestHandler = async ({ params, request, locals }) => {
	if (!locals.user || locals.user.role !== 'admin') return json({ message: 'Unauthorized' }, { status: 403 });

	const id = Number(params.id!);
	const data = await request.json();
	const parsed = RequestStatusSchema.safeParse(data);

	if (!parsed.success) {
		return json({ message: parsed.error.issues[0].message }, { status: 400 });
	}

	const statusData = parsed.data;

	const inventoryReq = await locals.db.inventory_requests.update({
		where: { id },
		data: {
			status: statusData.status,
			admin_note: statusData.admin_note || null,
			updated_at: new Date()
		}
	});

	// Create notification
	await locals.db.notifications.create({
		data: {
			user_id: inventoryReq.user_id,
			title: 'Status Permintaan Diperbarui',
			message: `Permintaan ${inventoryReq.request_number} Anda kini berstatus: ${statusData.status}`,
			type: statusData.status === 'rejected' ? 'error' : 'info',
			is_read: false,
			created_at: new Date(),
			updated_at: new Date()
		}
	});

	return json(inventoryReq);
};

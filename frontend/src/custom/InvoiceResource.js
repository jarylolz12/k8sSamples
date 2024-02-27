/** @format */

import moment from "moment";

const Invoice = class {
	#INVOICES = [];

	constructor(data) {
		this.#INVOICES = data;
	}

	#CAST = (p) => {
		p.invoice_no = p.invoice_number;
		p.invoice_date = moment.utc(p.invoice_date).format("MMM DD, YYYY");
		p.shipment_reference = p.shipment_reference_number;
		p.due_date = moment.utc(p.due_date).format("MMM DD, YYYY");
		p.amount = p.total_amount;
		p.paid = p.payment_status === "Paid";
		p.status = p.payment_status === "Paid" ? "Paid" : "Unpaid";
		p.billing_status = [
			"All Bills",
			p.payment_status === "Paid" ? "Paid" : "Unpaid",
		];

		return p;
	};

	#PROCESS_DATA = (data) => {
		if (data.length > 0) {
			data = data.map((invoice) => {
				invoice = this.#CAST(invoice);

				return {
					...invoice,
				};
			});
		}

		return data;
	};

	all() {
		return this.#PROCESS_DATA(this.#INVOICES);
	}
	pending() {
		return this.#INVOICES;
	}
	completed() {
		return this.#INVOICES;
	}
	invoices() {
		return this.#INVOICES;
	}
};

export default Invoice;

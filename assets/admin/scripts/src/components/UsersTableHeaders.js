import React from "react";

class UsersTableHeaders extends React.Component {
	onClickHeader(newOrder) {
		if (newOrder !== this.props.request.orderby) {
			this.props.onRequestChange({
				orderby: newOrder,
				order: "asc"
			});
		} else {
			this.props.onRequestChange({
				orderby: newOrder,
				order:
					this.props.request.order.toLowerCase() === "asc"
						? "desc"
						: "asc"
			});
		}
	}

	render() {
		let { order, orderby } = this.props.request;
		order = order.toLowerCase();

		let usernameClasses = "sortable desc";
		let nameClasses = "sortable desc";

		if (orderby === "user_login") {
			usernameClasses = `sorted ${order}`;
		} else if (orderby === "name") {
			nameClasses = `sorted ${order}`;
		}

		return (
			<thead>
				<tr>
					<th
						scope="col"
						id="username"
						className={`manage-column column-username column-primary ${usernameClasses}`}
						onClick={() => this.onClickHeader("user_login")}
					>
						<p>
							<span>Username</span>
							<span className="sorting-indicator" />
						</p>
					</th>
					<th
						scope="col"
						id="name"
						className={`manage-column column-name column-primary ${nameClasses}`}
						onClick={() => this.onClickHeader("name")}
					>
						<p>
							<span>Name</span>
							<span className="sorting-indicator" />
						</p>
					</th>
					<th
						scope="col"
						id="email"
						className="manage-column column-email"
					>
						<span>E-mail address</span>
					</th>
					<th
						scope="col"
						id="email"
						className="manage-column column-role"
					>
						Role(s)
					</th>
				</tr>
			</thead>
		);
	}
}

export default UsersTableHeaders;

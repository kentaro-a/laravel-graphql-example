
import React from 'react'
import Head from 'next/head'
import UserAdd from 'components/UserAdd'
import {ApolloClient, gql, InMemoryCache, HttpLink} from '@apollo/client'

class Users extends React.Component<{user_list: []}, {user_list: []}> {

	constructor(props) {
		super(props)
		this.state = {user_list: props.user_list}
		this.deleteItem = this.deleteItem.bind(this)
	}

	static async getInitialProps({ Component, router, ctx }) {
		try {
			const client = new ApolloClient({
				uri: `${process.env.NEXT_PUBLIC_BACKEND_BASE_URI}/api/graphql`,
				cache: new InMemoryCache(),
			})
			const res = await client.query({
				query: gql`
					query{
						user_list(orderBy: [{column: ID, order:DESC}]){
							id
							name
							last_login_at
						}
					}
				`
			})
			return {user_list: res.data.user_list} 

		} catch (e) {
			console.log(e)
		}
	}


	deleteItem() {
		console.log(process.env.NEXT_PUBLIC_BACKEND_BASE_URI)
		let user_list = this.state.user_list
		user_list.shift()
		this.setState({user_list: user_list})
	}	
	


	render() {
		return (
			<>
				<Head>
					<title>XXXX</title>
				</Head>
				<div>
					<UserAdd />
				</div>
				<div>
					<button onClick={this.deleteItem}>del</button>
					<div>
						{
							this.state.user_list.length > 0 ??
							(
								<table>
									<tbody>
									{this.state.user_list.map((u, i) => {
										return (
											<tr key={i}>
												<td>{u.id}</td>
												<td>{u.name}</td>
												<td>{u.last_login_at}</td>
											</tr>
										)
									})}
									</tbody>
								</table>
							)
						}
					</div>
				</div>
			</>
		)
	}
}

export default Users 

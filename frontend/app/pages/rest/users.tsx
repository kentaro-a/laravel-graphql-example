
import React from 'react'
import Head from 'next/head'
import UserAdd from 'components/UserAdd'
import {useContext, useState} from 'react';
import {Context} from 'pages/_app'
import ActionType from "context/ActionType"




const Users = (props) => {
	const [user_list, setUserList] = useState(props.user_list);
	const {state, dispatch} = useContext(Context) 

	const deleteItem = (user) => {
		let _user_list = user_list
		_user_list = _user_list.filter(v => v.id !== user.id)
		setUserList([..._user_list])
	}	
	
	const login = (user) => {
		dispatch({type: ActionType.LOGIN, payload: user})
	}

	const refreshUsers = async () => {
		const _user_list = await getUsers()
		setUserList([..._user_list])
	}

	return (
		<>
			<Head>
				<title>Users</title>
			</Head>

			
			<div className="row">
				<div className="col-3">
					<UserAdd refreshUsers={refreshUsers}/>
				</div>
				<div className="col-9">
					{
						user_list.length > 0 && 
						(
							<table className="table table-striped">
								<tbody>
									{user_list.map((u,i) => {
										return (
											<tr key={i}>
												<td>{u.id}</td>
												<td>{u.name}</td>
												<td>{u.mail}</td>
												<td><button className="btn btn-info" onClick={() => login(u)}>login</button></td>
												<td><button className="btn btn-danger" onClick={() => deleteItem(u)}>del</button></td>
												
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

const getUsers = async () => {
	let url = `${process.env.NEXT_PUBLIC_BACKEND_BASE_URI}/api/rest/user/list`
	const res = await fetch(url)
	const json = await res.json()
	return json.results

}


Users.getInitialProps = async () => {
	const user_list = await getUsers()
	return {user_list}
} 

export default Users 

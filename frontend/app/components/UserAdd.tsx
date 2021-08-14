
import {useState} from 'react';
import users from 'pages/rest/users';


type User = {
	name: string
	mail: string
	password: string
}

const UserAdd = ({refreshUsers}) => {
	
	const [user, setUser] = useState({name: "", mail: "", password: ""});
	const [errors, setErrors] = useState([]);

	const change = (e) => {
		setUser({...user, [e.target.name]: e.target.value})
	}

	const register = async () => {
		try {
			const body = {
				method: "POST",
				headers: {"Content-Type": "application/json"},
				body: JSON.stringify(user), 
			}
			const res = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_BASE_URI}/api/rest/user/add`, body)
			const json = await res.json()
			if (json.status !== 200) {
				const error_messages = Object.values(json.errors).reduce((a,b) => a.concat(b))
				setErrors(error_messages)	
			} else {
				setErrors([])	
				refreshUsers()	
			}

		} catch (e) {
			console.log(e)
		}
	}

	return (
		<div>
			{errors.length>0 &&
				<div style={{backgroundColor: "red", color: "#fff"}}>
					{errors.map(v => {
						return (
							<p>{v}</p>
						)
					})}
				</div>
			}
			<div className="form-group">
				<label for="name">Name</label>
				<input className="form-control" id="name" name="name" type="text" value={user.name} onChange={(e)=>change(e)} />
			</div>
			<div className="form-group">
				<label for="name">Mail</label>
				<input className="form-control" id="mail" name="mail" type="text" value={user.mail} onChange={(e)=>change(e)} />
			</div>
			<div className="form-group">
				<label for="password">Password</label>
				<input className="form-control" id="password" name="password" type="password" value={user.password} onChange={(e)=>change(e)} />
			</div>
			<div className="form-group mt-3">
				<button className="btn btn-primary" onClick={register}>Register</button>
			</div>
		</div>
	)

}


export default UserAdd

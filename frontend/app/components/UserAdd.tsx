
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
			const res = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL_OUT_OF_CONTAINER}/api/rest/user/add`, body)
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
			<div>
				Name: <input name="name" type="text" value={user.name} onChange={(e)=>change(e)} />
			</div>
			<div>
				Mail: <input name="mail" type="text" value={user.mail} onChange={(e)=>change(e)} />
			</div>
			<div>
				Password: <input name="password" type="password" value={user.password} onChange={(e)=>change(e)} />
			</div>
			<div>
				<button onClick={register}>register</button>
			</div>
		</div>
	)

}


export default UserAdd

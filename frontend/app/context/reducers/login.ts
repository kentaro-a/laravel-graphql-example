import Action from "../Action"
import State from "../State"

const f = (state: State, action: Action): State => {
	const after_state = {...state, user: action.payload}
	return after_state 
}
export default f


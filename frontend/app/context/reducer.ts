import Action from "./Action"
import ActionType from "./ActionType"
import State from "./State"
import login from "./reducers/login"

const reducer = (state: State, action: Action): State => {
	switch (action.type) {
		case ActionType.LOGIN:
			state = login(state, action) 
			return state 
		default:
			return state
	}
}

export default reducer

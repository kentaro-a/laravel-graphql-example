import Action from "./Action"
import ActionType from "./ActionType"
import State from "./State"
import increment from "./reducers/increment"
import decrement from "./reducers/decrement"

const reducer = (state: State, action: Action): State => {
	switch (action.type) {
		case ActionType.INC:
			state = increment(state, action) 
			return state 
		case ActionType.DEC:
			state = decrement(state, action) 
			return state 
		default:
			return state
	}
}

export default reducer

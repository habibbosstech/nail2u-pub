import {createStore, applyMiddleware, compose} from 'redux';
import thunkMiddleware from 'redux-thunk';
//import {createLogger} from 'redux-logger';
import rootReducer from '../redux/reducers/index';


import storage from 'redux-persist/lib/storage'
import {persistStore, persistReducer} from 'redux-persist'

const persistConfig = {
    key: 'root',
    storage,
}
const persistedReducer = persistReducer(persistConfig, rootReducer)
//const middleware = applyMiddleware(thunkMiddleware,createLogger())
const middleware = applyMiddleware(thunkMiddleware)

const store = createStore(
    persistedReducer,
    compose(
        middleware,
        window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__(),
    )
);
let persistor = persistStore(store)

export  {store, persistor}

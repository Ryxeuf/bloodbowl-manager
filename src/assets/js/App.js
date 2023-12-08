import {HydraAdmin} from "@api-platform/admin";
import {PositionList} from "./component/position/PositionList";
import {FactionList} from "./component/faction/FactionList";
import {PositionShow} from "./component/position/PositionShow";
import {FactionShow} from "./component/faction/FactionShow";
import {SkillShow} from "./component/skill/SkillShow";
import {SkillList} from "./component/skill/SkillList";
import {UserList} from "./component/user/UserList";
import {UserShow} from "./component/user/UserShow";
import {UserEdit} from "./component/user/UserEdit";
import {SkillEdit} from "./component/skill/SkillEdit";
import {PositionEdit} from "./component/position/PositionEdit";
import {FactionEdit} from "./component/faction/FactionEdit";
import {Resource} from "react-admin";
import {ENTRYPOINT} from "./utils";
import {authProvider, dataProvider} from './provider';

// Replace with your own API entrypoint
// For instance if https://example.com/api/books is the path to the collection of book resources, then the entrypoint is https://example.com/api
export const App = () => {

    return <HydraAdmin
        dataProvider={dataProvider}
        entrypoint={ENTRYPOINT}
        authProvider={authProvider}
        disableTelemetry
        requireAuth>
        {(permissions) => (
            <>
                {permissions?.indexOf('ROLE_ADMIN') !== -1 && <Resource name='users' list={UserList} show={UserShow} edit={UserEdit}/>}
                <Resource name='factions' list={FactionList} show={FactionShow} edit={permissions?.indexOf('ROLE_ADMIN') !== -1 ? FactionEdit : null}/>
                <Resource name='positions' list={PositionList} show={PositionShow} edit={permissions?.indexOf('ROLE_ADMIN') !== -1 ? PositionEdit : null}/>
                <Resource name='skills' list={SkillList} show={SkillShow} edit={permissions?.indexOf('ROLE_ADMIN') !== -1 ? SkillEdit : null}/>
            </>
        )}
    </HydraAdmin>
};
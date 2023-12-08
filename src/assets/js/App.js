import {HydraAdmin, ResourceGuesser} from "@api-platform/admin";
import {Resource} from "react-admin";
import {ENTRYPOINT} from "./utils";
import {authProvider, dataProvider} from './provider';
import {SkillEdit, SkillList, SkillShow} from "./component/skill";
import {FactionEdit, FactionList, FactionShow} from "./component/faction";
import {PositionEdit, PositionList, PositionShow} from "./component/position";
import {UserEdit, UserList, UserShow} from "./component/user";
import {TeamCreate, TeamEdit, TeamList, TeamShow} from "./component/team";

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
                <ResourceGuesser name='teams' create={TeamCreate} list={TeamList} show={TeamShow} edit={TeamEdit} />
            </>
        )}
    </HydraAdmin>
};
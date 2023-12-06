import {HydraAdmin, ResourceGuesser} from "@api-platform/admin";
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

// Replace with your own API entrypoint
// For instance if https://example.com/api/books is the path to the collection of book resources, then the entrypoint is https://example.com/api
export const App = () => (
    <HydraAdmin entrypoint="https://bloodbowl-manager.ddev.site:9103/api">
        <ResourceGuesser name='users' list={UserList} show={UserShow} edit={UserEdit} />
        <ResourceGuesser name='factions' list={FactionList} show={FactionShow} edit={FactionEdit} />
        <ResourceGuesser name='positions' list={PositionList} show={PositionShow} edit={PositionEdit} />
        <Resource name='skills' list={SkillList} show={SkillShow} edit={SkillEdit} />
    </HydraAdmin>
);
import {DatagridConfigurable, InfiniteList, ReferenceField, TextField, usePermissions} from "react-admin";

export const TeamList = props => {
    const {permissions} = usePermissions();

    return <InfiniteList {...props} exporter={false}>
        <DatagridConfigurable bulkActionButtons={false} rowClick="show">
            { permissions?.indexOf('ROLE_ADMIN') !== -1 &&
                <ReferenceField reference="users" source="user" link="show">
                    <TextField source="username"/>
                </ReferenceField>
            }
            <ReferenceField reference="factions" source="faction" link="show">
                <TextField source="name"/>
            </ReferenceField>
            <TextField source={"name"}/>
            <TextField source={"playType"}/>
            <TextField source={"playCategory"}/>
        </DatagridConfigurable>
    </InfiniteList>
};
import {
    ArrayField, ChipField,
    Datagrid, NumberField,
    ReferenceField,
    Show,
    SimpleShowLayout, SingleFieldList,
    TextField,
    usePermissions
} from "react-admin";

export const TeamShow = props => {
    const {permissions} = usePermissions();

    return <Show {...props}>
        <SimpleShowLayout>
            {permissions?.indexOf('ROLE_ADMIN') !== -1 &&
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
            <ArrayField source="players">
                <Datagrid bulkActionButtons={false}>
                    <TextField source="number"/>
                    <TextField source="name"/>
                    <TextField source="positionInfos.name"/>
                    <TextField source="positionInfos.m"/>
                    <TextField source="positionInfos.f"/>
                    <TextField source="positionInfos.ag"/>
                    <TextField source="positionInfos.cp"/>
                    <TextField source="positionInfos.ar"/>
                    <NumberField source="positionInfos.cost"/>
                    <ArrayField source="positionInfos.skills">
                        <SingleFieldList linkType={false}>
                            <ChipField source="name" size="small" />
                        </SingleFieldList>
                    </ArrayField>
                </Datagrid>
            </ArrayField>
        </SimpleShowLayout>
    </Show>
};